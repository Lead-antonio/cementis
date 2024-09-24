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
     * Vérification si il y a un TEMPS DE CONDUITE JOUR ou NUIT.
     *
     */
    // public static function checkForInfraction($movements) {
    //     $utils = new Utils();
    //     $continueService = new ConduiteContinueService();
    //     $totalDriveDuration = 0; // Cumule des durées de DRIVE
    //     $applyNightCondition = false; // Indicateur pour appliquer la règle de nuit
    //     $infractionFound = false;
    //     $nightCondition = 2 * 3600; // 2 heures en secondes
    //     $dayCondition = 4 * 3600; // 4 heures en secondes
    //     $result = []; // Stocker la résulat final
        
    //     $truckService = new TruckService();

    //     $firstDriveDate = null; // Variable pour la première date de DRIVE
    //     $firstDriveHour = null; // Variable pour la première heure de DRIVE
    //     $lastDriveDate = null;  // Variable pour la dernière date de DRIVE
    //     $lastDriveHour = null; // Variable pour la dernière heure de DRIVE
        
    //     foreach ($movements as $index => $movement) {
    //         $immatricule = $truckService->getTruckPlateNumberByImei($movement['imei']);
    //         // Si on trouve un mouvement de type DRIVE, cumuler la durée
    //         if ($movement['type'] === 'DRIVE') {
    //             $driveDuration = $utils->convertTimeToSeconds($movement['duration']);
    //             $totalDriveDuration += $driveDuration;
    
    //             // Vérifier si le DRIVE chevauche la période de nuit (22h-4h)
    //             if ($continueService->isNightPeriod($movement['start_hour'], $movement['end_hour'])) {
    //                 $applyNightCondition = true;
    //             }

    //             // Si c'est le premier mouvement de type DRIVE, on enregistre les informations de départ
    //             if (is_null($firstDriveDate)) {
    //                 $firstDriveDate = $movement['start_date'];
    //                 $firstDriveHour = $movement['start_hour'];
    //             }

    //             // Toujours mettre à jour les informations du dernier mouvement DRIVE
    //             $lastDriveDate = $movement['end_date'];
    //             $lastDriveHour = $movement['end_hour'];
    
    //         } elseif ($movement['type'] === 'STOP') {
    //             // Si un STOP est trouvé, vérifier sa durée
    //             $stopDuration = $utils->convertTimeToSeconds($movement['duration']);
    
    //             if ($stopDuration < 1200) { // Si le STOP est inférieur à 20 minutes, on continue à cumuler
    //                 continue; // Continue la boucle pour voir le prochain mouvement
    //             } else {
    //                 // Si le STOP est supérieur à 20 minutes, on vérifie si le cumul des DRIVE dépasse les conditions
    //                 if (($applyNightCondition && $totalDriveDuration > $nightCondition) || 
    //                 (!$applyNightCondition && $totalDriveDuration > $dayCondition)) {
    //                     $event = $applyNightCondition ? "TEMPS DE CONDUITE CONTINUE NUIT" : "TEMPS DE CONDUITE CONTINUE JOUR";
    //                     $condition = $applyNightCondition ? $nightCondition : $dayCondition;
                        
    //                     $infractionFound = true;
    //                     $result = [
    //                         'imei' => $movement['imei'],
    //                         'chauffeur' => $movement['rfid'],
    //                         'vehicule' => $immatricule,
    //                         'type' => $event,
    //                         'distance' => 0, // Peut être calculé si besoin
    //                         'vitesse' => 0,
    //                         'odometer' => 0,
    //                         'duree_mouvement' => $totalDriveDuration, 
    //                         'duree_initial' => $condition, // Exemple, vous pouvez ajuster
    //                         'date_debut' => $firstDriveDate,
    //                         'date_fin' => $lastDriveDate, // Peut être ajusté si besoin
    //                         'heure_debut' => $firstDriveHour,
    //                         'heure_fin' => $lastDriveHour, // Peut être ajusté
    //                         'gps_debut' => "",
    //                         'gps_fin' => "",
    //                         'point' => ($totalDriveDuration - $condition) / 600, // Exemple, ajuster selon la logique
    //                         'insuffisance' => ($totalDriveDuration - $condition) // À calculer selon vos règles
    //                     ];
    //                 }
    
    //                 // Réinitialiser après un STOP > 20 minutes
    //                 $totalDriveDuration = 0;
    //                 $applyNightCondition = false;
    //                 $firstDriveStartDate = null; // Réinitialiser le premier DRIVE
    //                 $firstDriveStartHour = null;
    //             }
    //         }
    //     }
    //     return $result;
    // }
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
    
        // Variables pour heure de début et fin du premier et dernier DRIVE de la journée
        $firstDriveStartHour = null;
        $lastDriveEndHour = null;
    
        foreach ($movements as $index => $movement) {
            // Convertir la date de début du mouvement pour la journée
            $movementDate = Carbon::parse($movement['start_date']);
            $immatricule = $truckService->getTruckPlateNumberByImei($movement['imei']);
    
            // Initialiser la journée courante (si première itération)
            if (!$currentDayStart) {
                $currentDayStart = $movementDate->copy()->startOfDay(); // Début de la journée
                $currentDayEnd = $currentDayStart->copy()->addHours(24); // Fin de la journée (24h plus tard)
            }
    
            // Si le mouvement appartient à un jour suivant, vérifier les infractions du jour courant
            if ($movementDate->gte($currentDayEnd)) {
                // Vérifier s'il y a une infraction pour la journée précédente
                if ($totalDriveDuration > 0) {
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
                        'date_debut' => $currentDayStart->toDateString(),
                        'date_fin' => $end->toDateString(),
                        'heure_debut' => $firstDriveStartHour,
                        'heure_fin' => $lastDriveEndHour,
                        'point' => ($totalDriveDuration - $condition) / 600,
                        'insuffisance' => ($totalDriveDuration - $condition)
                    ];
                }
    
                // Réinitialiser les cumuls pour la nouvelle journée
                $totalDriveDuration = 0;
                $applyNightCondition = false;
                $currentDayStart = $movementDate->copy()->startOfDay(); // Nouvelle journée
                $currentDayEnd = $currentDayStart->copy()->addHours(24);
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
                            'date_debut' => $currentDayStart->toDateString(),
                            'date_fin' => $end->toDateString(),
                            'heure_debut' => $firstDriveStartHour,
                            'heure_fin' => $lastDriveEndHour,
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
                'date_debut' => $currentDayStart->toDateString(),
                'date_fin' => $end->toDateString(),
                'heure_debut' => $firstDriveStartHour,
                'heure_fin' => $lastDriveEndHour,
                'point' => ($totalDriveDuration - $condition) / 600,
                'insuffisance' => ($totalDriveDuration - $condition)
            ];
        }
    
        return $result;
    }

    // $array_not_infraction =  [
    //     0 =>  [
    //       "id" => 3,
    //       "calendar_id" => 6319,
    //       "start_date" => "2024-08-21",
    //       "start_hour" => "05:06:24",
    //       "end_date" => "2024-08-21",
    //       "end_hour" => "07:10:51",
    //       "duration" => "02:04:27",
    //       "type" => "STOP",
    //       "created_at" => "2024-09-21T07:58:25.000000Z",
    //       "updated_at" => "2024-09-21T07:58:25.000000Z",
    //       "deleted_at" => null,
    //       "imei" => "865135060663638",
    //       "rfid" => "38008A2558",
    //     ],
    //     1 =>  [
    //       "id" => 1,
    //       "calendar_id" => 6319,
    //       "start_date" => "2024-08-21",
    //       "start_hour" => "07:10:51",
    //       "end_date" => "2024-08-21",
    //       "end_hour" => "07:52:38",
    //       "duration" => "00:41:47",
    //       "type" => "DRIVE",
    //       "created_at" => "2024-09-21T07:58:25.000000Z",
    //       "updated_at" => "2024-09-21T07:58:25.000000Z",
    //       "deleted_at" => null,
    //       "imei" => "865135060663638",
    //       "rfid" => "38008A2558",
    //     ],
    //     2 =>  [
    //       "id" => 4,
    //       "calendar_id" => 6319,
    //       "start_date" => "2024-08-21",
    //       "start_hour" => "07:52:38",
    //       "end_date" => "2024-08-21",
    //       "end_hour" => "09:42:00",
    //       "duration" => "01:49:22",
    //       "type" => "STOP",
    //       "created_at" => "2024-09-21T07:58:25.000000Z",
    //       "updated_at" => "2024-09-21T07:58:25.000000Z",
    //       "deleted_at" => null,
    //       "imei" => "865135060663638",
    //       "rfid" => "38008A2558",
    //     ],
    //     3 =>  [
    //       "id" => 2,
    //       "calendar_id" => 6319,
    //       "start_date" => "2024-08-21",
    //       "start_hour" => "09:42:00",
    //       "end_date" => "2024-08-21",
    //       "end_hour" => "10:10:44",
    //       "duration" => "00:28:44",
    //       "type" => "DRIVE",
    //       "created_at" => "2024-09-21T07:58:25.000000Z",
    //       "updated_at" => "2024-09-21T07:58:25.000000Z",
    //       "deleted_at" => null,
    //       "imei" => "865135060663638",
    //       "rfid" => "38008A2558",
    //     ],
    //     4 =>  [
    //       "id" => 5,
    //       "calendar_id" => 6319,
    //       "start_date" => "2024-08-21",
    //       "start_hour" => "10:10:44",
    //       "end_date" => "2024-08-21",
    //       "end_hour" => "14:17:35",
    //       "duration" => "04:06:51",
    //       "type" => "STOP",
    //       "created_at" => "2024-09-21T07:58:25.000000Z",
    //       "updated_at" => "2024-09-21T07:58:25.000000Z",
    //       "deleted_at" => null,
    //       "imei" => "865135060663638",
    //       "rfid" => "38008A2558",
    //     ]
    // ];

    // $array_infraction =  [
    //     0 =>  [
    //       "id" => 3,
    //       "calendar_id" => 6319,
    //       "start_date" => "2024-08-21",
    //       "start_hour" => "05:06:24",
    //       "end_date" => "2024-08-21",
    //       "end_hour" => "07:10:51",
    //       "duration" => "02:04:27",
    //       "type" => "STOP",
    //       "created_at" => "2024-09-21T07:58:25.000000Z",
    //       "updated_at" => "2024-09-21T07:58:25.000000Z",
    //       "deleted_at" => null,
    //       "imei" => "865135060663638",
    //       "rfid" => "38008A2558",
    //     ],
    //     1 =>  [
    //       "id" => 1,
    //       "calendar_id" => 6319,
    //       "start_date" => "2024-08-21",
    //       "start_hour" => "07:10:51",
    //       "end_date" => "2024-08-21",
    //       "end_hour" => "09:10:51",
    //       "duration" => "02:00:00",
    //       "type" => "DRIVE",
    //       "created_at" => "2024-09-21T07:58:25.000000Z",
    //       "updated_at" => "2024-09-21T07:58:25.000000Z",
    //       "deleted_at" => null,
    //       "imei" => "865135060663638",
    //       "rfid" => "38008A2558",
    //     ],
    //     2 =>  [
    //       "id" => 4,
    //       "calendar_id" => 6319,
    //       "start_date" => "2024-08-21",
    //       "start_hour" => "09:10:51",
    //       "end_date" => "2024-08-21",
    //       "end_hour" => "09:25:51",
    //       "duration" => "00:15:00",
    //       "type" => "STOP",
    //       "created_at" => "2024-09-21T07:58:25.000000Z",
    //       "updated_at" => "2024-09-21T07:58:25.000000Z",
    //       "deleted_at" => null,
    //       "imei" => "865135060663638",
    //       "rfid" => "38008A2558",
    //     ],
    //     3 =>  [
    //       "id" => 2,
    //       "calendar_id" => 6319,
    //       "start_date" => "2024-08-21",
    //       "start_hour" => "09:25:51",
    //       "end_date" => "2024-08-21",
    //       "end_hour" => "11:55:51",
    //       "duration" => "02:30:00",
    //       "type" => "DRIVE",
    //       "created_at" => "2024-09-21T07:58:25.000000Z",
    //       "updated_at" => "2024-09-21T07:58:25.000000Z",
    //       "deleted_at" => null,
    //       "imei" => "865135060663638",
    //       "rfid" => "38008A2558",
    //     ],
    //     4 =>  [
    //       "id" => 5,
    //       "calendar_id" => 6319,
    //       "start_date" => "2024-08-21",
    //       "start_hour" => "11:55:51",
    //       "end_date" => "2024-08-21",
    //       "end_hour" => "14:17:35",
    //       "duration" => "00:30:00",
    //       "type" => "STOP",
    //       "created_at" => "2024-09-21T07:58:25.000000Z",
    //       "updated_at" => "2024-09-21T07:58:25.000000Z",
    //       "deleted_at" => null,
    //       "imei" => "865135060663638",
    //       "rfid" => "38008A2558",
    //     ]
    // ];
    
      

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
                $allmovements = $mouvementService->getAllMouvementDuringCalendar($calendar->id);
                $organizeMovements = $mouvementService->organizeMovements($allmovements);
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
                            ->where('vehicule', $infraction['vehicule'])
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