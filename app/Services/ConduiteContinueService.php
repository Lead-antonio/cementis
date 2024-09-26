<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use App\Models\ImportExcel;
use App\Services\MovementService;
use App\Services\TruckService;
use App\Helpers\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ConduiteContinueService
{
    /**
     * Antonio
     * Vérification des infractions de conduite continue notifier par rapport à la  période du calendrier.
     *
     */
    public static function checkTempsConduiteContinueNotification($console){
        // Récupérer toutes les pénalités
        $infractions = Infraction::whereNotNull('calendar_id')
                                    ->where(function ($query) {
                                        $query->where('event', 'TEMPS DE CONDUITE CONTINUE NUIT')
                                            ->orWhere('event', 'TEMPS DE CONDUITE CONTINUE JOUR');
                                    })      
                                   ->orderBy('date_debut')
                                   ->orderBy('heure_debut')
                                   ->get();
        $mouvementService = new MovementService();
        $utils = new Utils();

        $limite = 0;
        // Tableau pour stocker les heures de conduite pour chaque chauffeur
        $updates = [];

        // Parcourir chaque pénalité
        foreach ($infractions as $infraction) {
            $calendar_date_debut = Carbon::parse($infraction->related_calendar->date_debut);
            $calendar_date_fin = $infraction->related_calendar->date_fin ? Carbon::parse($infraction->related_calendar->date_fin) : null;
            $calendar_delais_route = $infraction->related_calendar->delais_route;
            if ($infraction->event === "TEMPS DE CONDUITE CONTINUE JOUR") {
                // Règle de jour
                $limite = 4 * 3600;
            } elseif ($infraction->event === "TEMPS DE CONDUITE CONTINUE NUIT") {
                // Règle de nuit
                $limite = 2 * 3600;
            }


            $move_start = $utils->convertTimeToSeconds($infraction->heure_debut);
            $all_movements = $mouvementService->getAllMouvementDuringCalendar($infraction->calendar_id);
            if(!$all_movements->isEmpty()){
                $stopmovement = $mouvementService->getStopBehindGivingDateAndHour($all_movements, $infraction->date_debut, $infraction->heure_debut);
                if($stopmovement){
                    $stopTime =  $utils->convertTimeToSeconds($stopmovement->start_hour);
                    $duree_mouvement = ($stopTime - $move_start); 
                    $duree_infraction = $duree_mouvement - $limite;  
                    $point =  ($duree_mouvement - $limite)/600; 

    
                    $updates[] = [
                        'id' => $infraction->id,
                        'duree_initial' => $limite, 
                        'duree_infraction' => $duree_mouvement, 
                        'point' => $point,
                        'insuffisance' => $duree_infraction,
                        'heure_fin' => $stopmovement->start_hour,
                    ];
                }
            }

        }
        
        $console->withProgressBar($updates, function($update) {
            Infraction::where('id', $update['id'])
            ->update([
                'duree_initial' => $update['duree_initial'],
                'duree_infraction' => $update['duree_infraction'],
                'point' => $update['point'],
                'insuffisance' => $update['insuffisance'],
                'heure_fin' => $update['heure_fin']
            ]);
        });

        $console->info('Tous les Conduite continue sont tous vérifiés.');
            
    }

    /**
     * Antonio
     * Vérification du plage de nuit.
     *
     */
    public static function isNightPeriod($startHour, $endHour) {
        if (($startHour >= '04:00:00' && $endHour <= '22:00:00')) {
            // Règle de jour
            return false;
        } elseif ($startHour >= '22:00:00' || $endHour <= '04:00:00') {
            // Règle de nuit
            return true;
        } elseif (($startHour < '04:00:00' && $endHour > '22:00:00') || ($startHour < '04:00:00' && $endHour < '22:00:00')) {
            // Le trajet chevauche la journée et la nuit
            return true;
        } 
    }

    /**
     * Antonio
     * Get first and last date and time withe DRIVE type
     *
     */
    public static function getFirstDriveAndLastMovement($movements) {
        $firstDriveDateTime = null; // Variable pour stocker la première date et heure du DRIVE
        $lastMovementDateTime = null; // Variable pour stocker la dernière date et heure du dernier mouvement

        foreach ($movements as $movement) {
            // Vérifier le premier mouvement de type "DRIVE"
            if ($movement['type'] === 'DRIVE') {
                if (!$firstDriveDateTime) {
                    $firstDriveDateTime = [
                        'start_date' => $movement['start_date'],
                        'start_hour' => $movement['start_hour'],
                    ];
                }
            }
    
            // Capturer la dernière date et heure du dernier mouvement (DRIVE ou STOP)
            $lastMovementDateTime = [
                'end_date' => $movement['end_date'],
                'end_hour' => $movement['end_hour'],
            ];
        }
    
        // Retourner les deux informations
        return [
            'first_drive' => $firstDriveDateTime,
            'last_drive' => $lastMovementDateTime
        ];
    }

    /**
     * Antonio
     * Vérification si il y a un TEMPS DE CONDUITE JOUR ou NUIT.
     *
     */
    public static function checkForInfraction($movements) {
        $utils = new Utils();
        $continueService = new ConduiteContinueService();
        $truckService = new TruckService();
        $totalDriveDuration = 0;
        $applyNightCondition = false;
        $dayCondition = 4 * 3600; // 4 heures (jour)
        $nightCondition = 2 * 3600; // 2 heures (nuit)
        $result = [];
        $infractionFound = false;
    
        // Variables pour gérer le cumul par journée
        $currentDayStart = null;
        $currentDayEnd = null;
        $immatricule = null;
        $firstAndLastDrive = self::getFirstDriveAndLastMovement($movements);
        // dd($movements);
        // Variables pour heure de début et fin du premier et dernier DRIVE de la journée
        $firstDriveStartHour = null;
        $lastDriveEndHour = null;
    
        foreach ($movements as $index => $movement) {
            // Convertir la date de début du mouvement pour la journée
            $movementDate = Carbon::parse($movement['start_date'] . ' ' . $movement['start_hour']);
            $movementEndDate = Carbon::parse($movement['end_date'] . ' ' . $movement['end_hour']);
            $immatricule = $truckService->getTruckPlateNumberByImei($movement['imei']);

            // Initialiser la journée courante (si première itération)
            if (!$currentDayStart && !$currentDayEnd) {
                $currentDayStart = $movementDate; // Début de la journée
                $currentDayEnd = $movementDate->addHours(24);
            }
            
    
            // Si le mouvement appartient à un jour suivant, vérifier les infractions du jour courant
            if ($movementDate->between($currentDayStart, $currentDayEnd) && $movementEndDate->between($currentDayStart, $currentDayEnd)) {
                // Vérifier s'il y a une infraction pour la journée précédente
                if ($totalDriveDuration > 0) {
                    $event = $applyNightCondition ? "TEMPS DE CONDUITE CONTINUE NUIT" : "TEMPS DE CONDUITE CONTINUE JOUR";
                    $condition = $applyNightCondition ? $nightCondition : $dayCondition;
                    $first = Carbon::parse($currentDayStart->toDateString() . ' ' . $firstDriveStartHour);
                    $end = $first->addSeconds($totalDriveDuration);
    
                    $infractionFound = true;
                    $result[] = [
                        'calendar_id' => $movement['calendar_id'],
                        'imei' => $movement['imei'],
                        'rfid' => $movement['rfid'],
                        'vehicule' => $immatricule,
                        'event' => $event,
                        'distance' => 0,
                        'distance_calendar' => 0,
                        'odometer' => 0,
                        'duree_infraction' => $totalDriveDuration,
                        'duree_initial' => $condition,
                        'date_debut' => $firstAndLastDrive['first_drive']['start_date'],
                        'date_fin' => $firstAndLastDrive['last_drive']['end_date'],
                        'heure_debut' => $firstAndLastDrive['first_drive']['start_hour'],
                        'heure_fin' => $firstAndLastDrive['last_drive']['end_hour'],
                        'point' => ($totalDriveDuration - $condition) / 600,
                        'insuffisance' => ($totalDriveDuration - $condition)
                    ];
                }
    
                // Réinitialiser les cumuls pour la nouvelle journée
                $totalDriveDuration = 0;
                $applyNightCondition = false;
                $firstDriveStartHour = null;  // Réinitialiser l'heure du premier DRIVE
                $lastDriveEndHour = null;     // Réinitialiser l'heure du dernier DRIVE
            }
    
            // Cumuler les durées de DRIVE dans la journée courante
            if ($movement['type'] === 'DRIVE') {
                $driveDuration = $utils->convertTimeToSeconds($movement['duration']);
                $totalDriveDuration += $driveDuration;
    
                // Enregistrer l'heure de début du premier DRIVE
                if (!$firstDriveStartHour) {
                    $firstDriveStartHour = $movement['start_hour'];
                }
    
                // Toujours mettre à jour l'heure de fin du dernier DRIVE
                $lastDriveEndHour = $movement['end_hour'];
    
                // Vérifier si la période DRIVE chevauche la nuit
                if ($continueService->isNightPeriod($movement['start_hour'], $movement['end_hour'])) {
                    $applyNightCondition = true;
                }
            }
    
            // Gérer les STOP dans la journée courante
            if ($movement['type'] === 'STOP') {
                $stopDuration = $utils->convertTimeToSeconds($movement['duration']);

                $stopDurationThreshold = $applyNightCondition ? 900 : 1200;
    
                // Si un STOP est inférieur à 20 minutes, continuer à cumuler la durée de conduite
                if ($stopDuration < $stopDurationThreshold) {
                    continue; // Ignorer ce STOP et passer au mouvement suivant
                }
    
                // Si un STOP supérieur à 20 minutes est trouvé, vérifier les infractions
                if ($stopDuration >= $stopDurationThreshold) {
                    if (($applyNightCondition && $totalDriveDuration > $nightCondition) || 
                        (!$applyNightCondition && $totalDriveDuration > $dayCondition)) {
                        $event = $applyNightCondition ? "TEMPS DE CONDUITE CONTINUE NUIT" : "TEMPS DE CONDUITE CONTINUE JOUR";
                        $condition = $applyNightCondition ? $nightCondition : $dayCondition;
                        $first = Carbon::parse($currentDayStart->toDateString() . ' ' . $firstDriveStartHour);
                        $end = $first->copy()->addSeconds($totalDriveDuration);
    
                        $infractionFound = true;
                        $result[] = [
                            'calendar_id' => $movement['calendar_id'],
                            'imei' => $movement['imei'],
                            'rfid' => $movement['rfid'],
                            'vehicule' => $immatricule,
                            'event' => $event,
                            'distance' => 0,
                            'distance_calendar' => 0,
                            'odometer' => 0,
                            'duree_infraction' => $totalDriveDuration,
                            'duree_initial' => $condition,
                            'date_debut' => $firstAndLastDrive['first_drive']['start_date'],
                            'date_fin' => $firstAndLastDrive['last_drive']['end_date'],
                            'heure_debut' => $firstAndLastDrive['first_drive']['start_hour'],
                            'heure_fin' => $firstAndLastDrive['last_drive']['end_hour'],
                            'point' => ($totalDriveDuration - $condition) / 600,
                            'insuffisance' => ($totalDriveDuration - $condition)
                        ];
                    }
    
                    // Réinitialiser après un STOP > 20 minutes
                    $totalDriveDuration = 0;
                    $applyNightCondition = false;
                    $firstDriveStartHour = null;  // Réinitialiser l'heure du premier DRIVE
                    $lastDriveEndHour = null;     // Réinitialiser l'heure du dernier DRIVE
                }
            }
        }
    
        // Vérifier à la fin de la boucle s'il reste du temps de conduite pour la journée courante
        $condition = $applyNightCondition ? $nightCondition : $dayCondition;
        if ($totalDriveDuration > $condition) {
            $event = $applyNightCondition ? "TEMPS DE CONDUITE CONTINUE NUIT" : "TEMPS DE CONDUITE CONTINUE JOUR";
            $first = Carbon::parse($currentDayStart->toDateString() . ' ' . $firstDriveStartHour);
            $end = $first->copy()->addSeconds($totalDriveDuration);

    
            $infractionFound = true;
            $result[] = [
                'calendar_id' => $movement['calendar_id'],
                'imei' => $movement['imei'],
                'rfid' => $movements[0]['rfid'], // Assurez-vous que cela prend le bon chauffeur
                'vehicule' => $immatricule,
                'event' => $event,
                'distance' => 0,
                'distance_calendar' => 0,
                'odometer' => 0,
                'duree_infraction' => $totalDriveDuration,
                'duree_initial' => $condition,
                'date_debut' => $firstAndLastDrive['first_drive']['start_date'],
                'date_fin' => $firstAndLastDrive['last_drive']['end_date'],
                'heure_debut' => $firstAndLastDrive['first_drive']['start_hour'],
                'heure_fin' => $firstAndLastDrive['last_drive']['end_hour'],
                'point' => ($totalDriveDuration - $condition) / 600,
                'insuffisance' => ($totalDriveDuration - $condition)
            ];
        }
    
        return $result;
    }

    /**
     * Antonio
     * Vérification des infractions de conduite continue notifier par rapport à la  période du calendrier.
     *
     */
    public static function checkTempsConduiteContinueCumul($console){
        try{
            $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
            $startDate = Carbon::now()->subMonths(2)->endOfMonth();
            $endDate = Carbon::now()->startOfMonth();

            $mouvementService = new MovementService();
            $continueService = new ConduiteContinueService();
            $data_infraction = [];

            $calendars = ImportExcel::where('import_calendar_id', $lastmonth)->get();
            $console->withProgressBar($calendars, function($calendar) use ($mouvementService, $continueService, &$data_infraction) {
                    $allmovements = $mouvementService->getAllMouvementDuringCalendar(3);
                    $organizeMovements = $mouvementService->organizeMovements($allmovements);  //$calendar->id
                    $infraction = $continueService->checkForInfraction($organizeMovements);
                    if($infraction){
                        $data_infraction = array_merge($data_infraction,$infraction);
                    }
            });

            if (!empty($data_infraction)) {
                dd($data_infraction);
                try {
                    DB::beginTransaction(); // Démarre la transaction

                    foreach ($data_infraction as $infraction) {
                        // Rechercher une entrée existante avec les mêmes colonnes uniques
                        $existingInfraction = DB::table('infraction')
                            ->where('calendar_id', $infraction['calendar_id'])
                            ->where('imei', $infraction['imei'])
                            ->where('rfid', $infraction['rfid'])
                            // ->where('vehicule', $infraction['vehicule'])
                            ->where('event', $infraction['event'])
                            ->where('date_debut', $infraction['date_debut'])
                            ->where('date_fin', $infraction['date_fin'])
                            ->where('heure_debut', $infraction['heure_debut'])
                            ->where('heure_fin', $infraction['heure_fin'])
                            ->first();

                        // Si une entrée existe
                        if (!$existingInfraction) {
                            DB::table('infraction')->insert($infraction);
                        }
                    }

                    DB::commit(); // Valide la transaction
                    $console->info(count($data_infraction) . ' infractions traitées avec succès.');
                } catch (Exception $e) {
                    DB::rollBack(); // Annule la transaction en cas d'erreur
                    $console->error('L\'insertion des infractions a échoué : ' . $e->getMessage());
                    Log::error('Erreur d\'insertion dans infraction: ' . $e->getMessage());
                }
            } 
        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error('Erreur lors de la vérification du temps du conduite continue qui cumul: ' . $e->getMessage());
        }
    }
    
}