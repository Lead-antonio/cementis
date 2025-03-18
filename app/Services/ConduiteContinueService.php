<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use App\Models\ImportExcel;
use App\Services\MovementService;
use App\Services\ConduiteMaximumService;
use App\Services\TruckService;
use App\Helpers\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicule;

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
     * Vérification si il y a un TEMPS DE CONDUITE JOUR ou NUIT 
     * Avec règle de nuit et jour et point selon la durée.
     */
    // public static function checkForInfraction($movements)
    // {
    //     $utils = new Utils();
    //     $continueService = new ConduiteContinueService();
    //     $truckService = new TruckService();
    //     $totalDriveDuration = 0;
    //     $applyNightCondition = false;
    //     $dayCondition = 4 * 3600; // 4 heures (jour)
    //     $nightCondition = 2 * 3600; // 2 heures (nuit)
    //     $result = [];
    //     $infractionFound = false;

    //     $immatricule = null;

    //     // Variables pour date et heure de début et fin du premier et dernier DRIVE de la journée
    //     $first_drive_start_date = null;
    //     $last_drive_end_date = null;
    //     $first_drive_start_hour = null;
    //     $last_drive_end_hour = null;

    //     foreach ($movements as $index => $movement) {
    //         $immatricule = $truckService->getTruckPlateNumberByImei($movement['imei']);
    //         // Cumuler les durées de DRIVE dans la journée courante
    //         if ($movement['type'] === 'DRIVE') {
    //             $driveDuration = $utils->convertTimeToSeconds($movement['duration']);
    //             $totalDriveDuration += $driveDuration;

    //             // Enregistrer la date  de début du premier DRIVE
    //             if (!$first_drive_start_date) {
    //                 $first_drive_start_date = $movement['start_date'];
    //             }

    //             // Enregistrer l'heure de début du premier DRIVE
    //             if (!$first_drive_start_hour) {
    //                 $first_drive_start_hour = $movement['start_hour'];
    //             }

    //             // Toujours mettre à jour la date de fin du dernier DRIVE
    //             $last_drive_end_date = $movement['end_date'];

    //             // Toujours mettre à jour l'heure de fin du dernier DRIVE
    //             $last_drive_end_hour = $movement['end_hour'];

    //             // Vérifier si la période DRIVE chevauche la nuit
    //             if ($utils->isNightPeriod($movement['start_hour'], $movement['end_hour'])) {
    //                 $applyNightCondition = true;
    //             }
    //         }

    //         // Gérer les STOP dans la journée courante
    //         if ($movement['type'] === 'STOP') {
    //             $stopDuration = $utils->convertTimeToSeconds($movement['duration']);
    //             $stopDurationThreshold = $applyNightCondition ? 900 : 1200;

    //             if ($stopDuration >= $stopDurationThreshold) {
    //                 if (($applyNightCondition && $totalDriveDuration > $nightCondition) || 
    //                     (!$applyNightCondition && $totalDriveDuration > $dayCondition)) {
    //                     $event = $applyNightCondition ? "TEMPS DE CONDUITE CONTINUE NUIT" : "TEMPS DE CONDUITE CONTINUE JOUR";
    //                     $condition = $applyNightCondition ? $nightCondition : $dayCondition;

    //                     $infractionFound = true;
    //                     $result[] = [
    //                         'calendar_id' => $movement['calendar_id'],
    //                         'imei' => $movement['imei'],
    //                         'rfid' => $movement['rfid'],
    //                         'vehicule' => $immatricule,
    //                         'event' => $event,
    //                         'distance' => 0,
    //                         'distance_calendar' => 0,
    //                         'odometer' => 0,
    //                         'duree_infraction' => $totalDriveDuration,
    //                         'duree_initial' => $condition,
    //                         'date_debut' => $first_drive_start_date,
    //                         'date_fin' => $last_drive_end_date,
    //                         'heure_debut' => $first_drive_start_hour,
    //                         'heure_fin' => $last_drive_end_hour,
    //                         'point' => ($totalDriveDuration - $condition) / 600,
    //                         'insuffisance' => ($totalDriveDuration - $condition)
    //                     ];
    //                 }

    //                 $totalDriveDuration = 0;
    //                 $applyNightCondition = false;
    //                 $first_drive_start_hour = null;
    //                 $last_drive_end_hour = null;
    //                 $first_drive_start_date = null;
    //                 $last_drive_end_date = null;
    //             }
    //         }
    //     }
    //     return $result;
    // }

    /**
     * Antonio
     * Vérification si il y a un TEMPS DE CONDUITE JOUR ou NUIT.
     * Sans règle de nuit et jour et 1 point à chaque infraction
     */
    public static function checkForInfraction($movements)
    {
        $utils = new Utils();
        $continueService = new ConduiteContinueService();
        $penaliteService = new PenaliteService();
        $truckService = new TruckService();
        $totalDriveDuration = 0;
        $condition = (4 * 3600) + 600; // 4 heures 10 minutes
        $result = [];
        $infractionFound = false;

        $immatricule = null;

        // Variables pour date et heure de début et fin du premier et dernier DRIVE de la journée
        $first_drive_start_date = null;
        $last_drive_end_date = null;
        $first_drive_start_hour = null;
        $last_drive_end_hour = null;

        foreach ($movements as $index => $movement) {
            $immatricule = $truckService->getTruckPlateNumberByImei($movement['imei']);
            // Cumuler les durées de DRIVE dans la journée courante
            if ($movement['type'] === 'DRIVE') {
                $driveDuration = $utils->convertTimeToSeconds($movement['duration']);
                $totalDriveDuration += $driveDuration;

                // Enregistrer la date  de début du premier DRIVE
                if (!$first_drive_start_date) {
                    $first_drive_start_date = $movement['start_date'];
                }

                // Enregistrer l'heure de début du premier DRIVE
                if (!$first_drive_start_hour) {
                    $first_drive_start_hour = $movement['start_hour'];
                }

                // Toujours mettre à jour la date de fin du dernier DRIVE
                $last_drive_end_date = $movement['end_date'];

                // Toujours mettre à jour l'heure de fin du dernier DRIVE
                $last_drive_end_hour = $movement['end_hour'];
            }

            // Gérer les STOP dans la journée courante
            if ($movement['type'] === 'STOP') {
                $stopDuration = $utils->convertTimeToSeconds($movement['duration']);
                $stopDurationThreshold = 1200;

                if ($stopDuration >= $stopDurationThreshold) {
                    if ($totalDriveDuration > $condition) {
                        $event = "TEMPS DE CONDUITE CONTINUE";
                        $point = $penaliteService->getPointPenaliteByEventType($event);

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
                            'date_debut' => $first_drive_start_date,
                            'date_fin' => $last_drive_end_date,
                            'heure_debut' => $first_drive_start_hour,
                            'heure_fin' => $last_drive_end_hour,
                            'point' => $point,
                            'insuffisance' => ($totalDriveDuration - $condition)
                        ];
                    }

                    $totalDriveDuration = 0;
                    $first_drive_start_hour = null;
                    $last_drive_end_hour = null;
                    $first_drive_start_date = null;
                    $last_drive_end_date = null;
                }
            }
        }
        return $result;
    }  

    /**
     * Antonio
     * Vérification des infractions de conduite continue cumul par rapport à la  période du calendrier.
     *
     */
    // public static function checkTempsConduiteContinueCumul($console, $start_date, $end_date){
    //     try{
    //         $mouvementService = new MovementService();
    //         $continueService = new ConduiteContinueService();
    //         $calendarService = new CalendarService();
    //         $all_trucks = Vehicule::all();
            
    //         $data_infraction = [];


    //         foreach($all_trucks as $truck){
    //             $imei = $truck->imei;
    //             $all_journey = $calendarService->getAllWorkJouneys($imei ,$start_date, $end_date);
    //             if(is_array($all_journey)){

    //                 $journeyCount = count($all_journey);
                   
    //                 $console->withProgressBar($journeyCount, function($progressBar) use ($all_journey,$mouvementService, $continueService, &$data_infraction, $imei) {
    //                     foreach($all_journey as $journey){
    //                         $allmovements = $mouvementService->getAllMovementByJourney($imei, $journey['start'], $journey['end']);
    //                         $infraction = $continueService->checkForInfraction($allmovements);
    //                         if($infraction){
    //                             $data_infraction = array_merge($data_infraction, $infraction);
    //                             $data_infraction = array_unique($data_infraction, SORT_REGULAR);
    //                         }
    
    //                         // Mettre à jour la barre de progression
    //                         $progressBar->advance();
    //                     }
                            
    //                 });
    //             }else{
    //                 // Si $all_journey n'est pas un tableau, afficher une erreur
    //                 $console->error("Erreur: Le résultat de getAllWorkJouneys pour le camion IMEI $imei n'est pas un tableau. Résultat: " . print_r($all_journey, true));
    //                 continue;
    //             }
    //         }
    //         if (!empty($data_infraction)) {
    //             try {
    //                 DB::beginTransaction(); // Démarre la transaction

    //                 foreach ($data_infraction as $infraction) {
    //                     // Rechercher une entrée existante avec les mêmes colonnes uniques
    //                     $existingInfraction = DB::table('infraction')
    //                         ->where('imei', $infraction['imei'])
    //                         ->where('rfid', $infraction['rfid'])
    //                         ->where('event', $infraction['event'])
    //                         ->where('date_debut', $infraction['date_debut'])
    //                         ->where('date_fin', $infraction['date_fin'])
    //                         ->where('heure_debut', $infraction['heure_debut'])
    //                         ->where('heure_fin', $infraction['heure_fin'])
    //                         ->first();

    //                     // Si une entrée existe
    //                     if (!$existingInfraction) {
    //                         DB::table('infraction')->insert($infraction);
    //                     }
    //                 }

    //                 DB::commit(); // Valide la transaction
    //                 $console->info(count($data_infraction) . ' infractions traitées avec succès.');
    //             } catch (Exception $e) {
    //                 DB::rollBack(); // Annule la transaction en cas d'erreur
    //                 $console->error('L\'insertion des infractions a échoué : ' . $e->getMessage());
    //                 Log::error('Erreur d\'insertion dans infraction: ' . $e->getMessage());
    //             }
    //         } 
             
    //     } catch (Exception $e) {
    //         return $e->getMessage();
    //         // Gestion des erreurs
    //         Log::error('Erreur lors de la vérification du temps du conduite continue qui cumul: ' . $e->getMessage());
    //     }
    //         // $all_journey = $calendarService->getAllJourneyDuringCalendar($console);
    // }

    public static function checkTempsConduiteContinueCumul($console){
        try{
            $mouvementService = new MovementService();
            $continueService = new ConduiteContinueService();
            $calendarService = new CalendarService();
            $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
            $existingTrucks = Vehicule::all(['nom', 'imei']);
            $truckData = $existingTrucks->pluck('imei', 'nom');
            $truckNames = $truckData->keys();
            $lastDayTwoMonthsAgo = Carbon::now()->subMonths(1)->endOfMonth()->toDateTimeString();
            
            // Récupération des calendriers
            $calendars = ImportExcel::whereIn('camion', $truckNames)
                ->where('import_calendar_id', $lastmonth)
                ->orderBy('date_debut', 'asc') // ou 'desc' pour ordre décroissant
                ->get();
        
            $data_infraction = [];
            $data_journeys = [];

            foreach($calendars as $calendar){
                $imei = $truckData->get(trim($calendar->camion));
                $calendar_start_date = new \DateTime($calendar->date_debut);
                $calendar_end_date = $calendar->date_fin ? new \DateTime($calendar->date_fin) : null;

                if ($calendar_end_date === null) {
                    $dureeEnHeures = floatval($calendar->delais_route);
                    if ($dureeEnHeures <= 1) {
                        $calendar_end_date = (clone $calendar_start_date)->setTime(23, 59, 59); // Fin de journée
                    } else {
                        $dureeEnJours = ceil($dureeEnHeures / 24);
                        $calendar_end_date = (clone $calendar_start_date)->modify('+' . $dureeEnJours . ' days');
                    }
                }
                $all_journey = $calendarService->getAllWorkJouneys($imei ,$calendar_start_date, $calendar_end_date);
                if(is_array($all_journey)){
                    $data_journeys = array_merge($data_journeys, $all_journey);

                    $journeyCount = count($all_journey);
                   
                    $console->withProgressBar($journeyCount, function($progressBar) use ($all_journey,$mouvementService, $continueService, &$data_infraction, $imei) {
                        foreach($all_journey as $journey){
                            $allmovements = $mouvementService->getAllMovementByJourney($imei, $journey['start'], $journey['end']);
                            $infraction = $continueService->checkForInfraction($allmovements);
                            if($infraction){
                                $data_infraction = array_merge($data_infraction, $infraction);
                                $data_infraction = array_unique($data_infraction, SORT_REGULAR);
                            }
    
                            // Mettre à jour la barre de progression
                            $progressBar->advance();
                        }
                            
                    });
                }else{
                    // Si $all_journey n'est pas un tableau, afficher une erreur
                    $console->error("Erreur: Le résultat de getAllWorkJouneys pour le camion IMEI $imei n'est pas un tableau. Résultat: " . print_r($all_journey, true));
                    continue;
                }
            }
            
            if (!empty($data_infraction)) {
                try {
                    DB::beginTransaction(); // Démarre la transaction

                    foreach ($data_infraction as $infraction) {
                        // Rechercher une entrée existante avec les mêmes colonnes uniques
                        $existingInfraction = DB::table('infraction')
                            ->where('imei', $infraction['imei'])
                            ->where('rfid', $infraction['rfid'])
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
            return $e->getMessage();
            // Gestion des erreurs
            Log::error('Erreur lors de la vérification du temps du conduite continue qui cumul: ' . $e->getMessage());
        }
            // $all_journey = $calendarService->getAllJourneyDuringCalendar($console);
    }

}