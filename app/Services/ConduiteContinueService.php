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
    // public static function isNightPeriod($startHour, $endHour) {
    //     if (($startHour >= '04:00:00' && $endHour <= '22:00:00')) {
    //         // Règle de jour
    //         return false;
    //     } elseif ($startHour >= '22:00:00' || $endHour <= '04:00:00') {
    //         // Règle de nuit
    //         return true;
    //     } elseif (($startHour < '04:00:00' && $endHour > '22:00:00') || ($startHour < '04:00:00' && $endHour < '22:00:00')) {
    //         // Le trajet chevauche la journée et la nuit
    //         return true;
    //     } 
    // }
    public function isNightPeriod($startHour, $endHour)
    {
        // Définir les heures de début et de fin de la nuit
        $nightStart = new \DateTime('22:00:00'); // 22h00
        $nightEnd = new \DateTime('04:00:00'); // 04h00 (du jour suivant)

        if ($startHour->format('H:i:s') >= $nightStart->format('H:i:s') || $endHour->format('H:i:s') <= $nightEnd->format('H:i:s')) {
            // Règle de nuit
            return true;
        } elseif (($startHour->format('H:i:s') < $nightEnd->format('H:i:s') && $endHour->format('H:i:s') > $nightStart->format('H:i:s')) || ($startHour->format('H:i:s') < $nightEnd->format('H:i:s') && $endHour->format('H:i:s') < $nightStart->format('H:i:s'))) {
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
    // public static function checkForInfraction($movements) {
    //     $utils = new Utils();
    //     $continueService = new ConduiteContinueService();
    //     $truckService = new TruckService();
    //     $totalDriveDuration = 0;
    //     $applyNightCondition = false;
    //     $dayCondition = 4 * 3600; // 4 heures (jour)
    //     $nightCondition = 2 * 3600; // 2 heures (nuit)
    //     $result = [];
    //     $infractionFound = false;
    
    //     // Variables pour gérer le cumul par journée
    //     $currentDayStart = null;
    //     $currentDayEnd = null;
    //     $immatricule = null;

    //     // Variables pour heure de début et fin du premier et dernier DRIVE de la journée
    //     $firstDriveStartHour = null;
    //     $lastDriveEndHour = null;
    
    //     foreach ($movements as $index => $movement) {
    //         // Convertir la date de début du mouvement pour la journée
    //         $movementDate = Carbon::parse($movement['start_date'] . ' ' . $movement['start_hour']);
    //         $movementEndDate = Carbon::parse($movement['end_date'] . ' ' . $movement['end_hour']);
    //         $immatricule = $truckService->getTruckPlateNumberByImei($movement['imei']);

    //         // Initialiser la journée courante (si première itération)
    //         if (!$currentDayStart && !$currentDayEnd) {
    //             $currentDayStart = $movementDate; // Début de la journée
    //             $currentDayEnd = $movementDate->addHours(24);
    //         }
            
    
    //         // Si le mouvement appartient à un jour suivant, vérifier les infractions du jour courant
    //         if ($movementDate->between($currentDayStart, $currentDayEnd) && $movementEndDate->between($currentDayStart, $currentDayEnd)) {
    //             // Vérifier s'il y a une infraction pour la journée précédente
    //             if ($totalDriveDuration > 0) {
    //                 $event = $applyNightCondition ? "TEMPS DE CONDUITE CONTINUE NUIT" : "TEMPS DE CONDUITE CONTINUE JOUR";
    //                 $condition = $applyNightCondition ? $nightCondition : $dayCondition;
    //                 $first = Carbon::parse($currentDayStart->toDateString() . ' ' . $firstDriveStartHour);
    //                 $end = $first->addSeconds($totalDriveDuration);
    
    //                 $infractionFound = true;
    //                 $result[] = [
    //                     'calendar_id' => $movement['calendar_id'],
    //                     'imei' => $movement['imei'],
    //                     'rfid' => $movement['rfid'],
    //                     'vehicule' => $immatricule,
    //                     'event' => $event,
    //                     'distance' => 0,
    //                     'distance_calendar' => 0,
    //                     'odometer' => 0,
    //                     'duree_infraction' => $totalDriveDuration,
    //                     'duree_initial' => $condition,
    //                     'date_debut' => $currentDayStart,
    //                     'date_fin' => $end->toDateString(),
    //                     'heure_debut' => $firstDriveStartHour,
    //                     'heure_fin' => $lastDriveEndHour,
    //                     'point' => ($totalDriveDuration - $condition) / 600,
    //                     'insuffisance' => ($totalDriveDuration - $condition)
    //                 ];
    //             }
    
    //             // Réinitialiser les cumuls pour la nouvelle journée
    //             $totalDriveDuration = 0;
    //             $applyNightCondition = false;
    //             $currentDayStart = $movementDate; // Nouvelle journée
    //             $currentDayEnd = $currentDayStart->addHours(24);
    //             $firstDriveStartHour = null;  // Réinitialiser l'heure du premier DRIVE
    //             $lastDriveEndHour = null;    // Réinitialiser l'heure du dernier DRIVE
    //         }
    
    //         // Cumuler les durées de DRIVE dans la journée courante
    //         if ($movement['type'] === 'DRIVE') {
    //             $driveDuration = $utils->convertTimeToSeconds($movement['duration']);
    //             $totalDriveDuration += $driveDuration;
    
    //             // Enregistrer l'heure de début du premier DRIVE
    //             if (!$firstDriveStartHour) {
    //                 $firstDriveStartHour = $movement['start_hour'];
    //             }
    
    //             // Toujours mettre à jour l'heure de fin du dernier DRIVE
    //             $lastDriveEndHour = $movement['end_hour'];
    
    //             // Vérifier si la période DRIVE chevauche la nuit
    //             if ($continueService->isNightPeriod($movement['start_hour'], $movement['end_hour'])) {
    //                 $applyNightCondition = true;
    //             }
    //         }
    
    //         // Gérer les STOP dans la journée courante
    //         if ($movement['type'] === 'STOP') {
    //             $stopDuration = $utils->convertTimeToSeconds($movement['duration']);

    //             $stopDurationThreshold = $applyNightCondition ? 900 : 1200;
    
    //             // Si un STOP est inférieur à 20 minutes, continuer à cumuler la durée de conduite
    //             if ($stopDuration < $stopDurationThreshold) {
    //                 continue; // Ignorer ce STOP et passer au mouvement suivant
    //             }
    
    //             // Si un STOP supérieur à 20 minutes est trouvé, vérifier les infractions
    //             if ($stopDuration >= $stopDurationThreshold) {
    //                 if (($applyNightCondition && $totalDriveDuration > $nightCondition) || 
    //                     (!$applyNightCondition && $totalDriveDuration > $dayCondition)) {
    //                     $event = $applyNightCondition ? "TEMPS DE CONDUITE CONTINUE NUIT" : "TEMPS DE CONDUITE CONTINUE JOUR";
    //                     $condition = $applyNightCondition ? $nightCondition : $dayCondition;
    //                     $first = Carbon::parse($currentDayStart->toDateString() . ' ' . $firstDriveStartHour);
    //                     $end = $first->addSeconds($totalDriveDuration);
    
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
    //                         'date_debut' => $currentDayStart,
    //                         'date_fin' => $end->toDateString(),
    //                         'heure_debut' => $firstDriveStartHour,
    //                         'heure_fin' => $lastDriveEndHour,
    //                         'point' => ($totalDriveDuration - $condition) / 600,
    //                         'insuffisance' => ($totalDriveDuration - $condition)
    //                     ];
    //                 }
    
    //                 // Réinitialiser après un STOP > 20 minutes
    //                 $totalDriveDuration = 0;
    //                 $applyNightCondition = false;
    //                 $firstDriveStartHour = null;  // Réinitialiser l'heure du premier DRIVE
    //                 $lastDriveEndHour = null;     // Réinitialiser l'heure du dernier DRIVE
    //             }
    //         }
    //     }
    
    //     // Vérifier à la fin de la boucle s'il reste du temps de conduite pour la journée courante
    //     $condition = $applyNightCondition ? $nightCondition : $dayCondition;
    //     if ($totalDriveDuration > $condition) {
    //         $event = $applyNightCondition ? "TEMPS DE CONDUITE CONTINUE NUIT" : "TEMPS DE CONDUITE CONTINUE JOUR";
    //         $first = Carbon::parse($currentDayStart->toDateString() . ' ' . $firstDriveStartHour);
    //         $end = $first->addSeconds($totalDriveDuration);

    
    //         $infractionFound = true;
    //         $result[] = [
    //             'calendar_id' => $movement['calendar_id'],
    //             'imei' => $movement['imei'],
    //             'rfid' => $movements[0]['rfid'], // Assurez-vous que cela prend le bon chauffeur
    //             'vehicule' => $immatricule,
    //             'event' => $event,
    //             'distance' => 0,
    //             'distance_calendar' => 0,
    //             'odometer' => 0,
    //             'duree_infraction' => $totalDriveDuration,
    //             'duree_initial' => $condition,
    //             'date_debut' => $currentDayStart,
    //             'date_fin' => $end->toDateString(),
    //             'heure_debut' => $firstDriveStartHour,
    //             'heure_fin' => $lastDriveEndHour,
    //             'point' => ($totalDriveDuration - $condition) / 600,
    //             'insuffisance' => ($totalDriveDuration - $condition)
    //         ];
    //     }
    
    //     return $result;
    // }

    // public static function checkForInfraction($movements)
    // {
    //     try {
    //         $utils = new Utils();
    //         $continueService = new ConduiteContinueService();
    //         $truckService = new TruckService();

    //         // Durées pour les infractions
    //         $dayCondition = 4 * 3600; // 4 heures (jour)
    //         $nightCondition = 2 * 3600; // 2 heures (nuit)
    //         $eightHourRest = 8 * 3600; // 8 heures de repos
    //         $tenHourRest = 10 * 3600; // 10 heures de repos
    //         $glidingWindow = 24 * 3600; // 24 heures glissantes

    //         $result = [];
    //         $totalDriveDuration = 0;
    //         $currentDayStart = null;
    //         $firstDriveStartHour = null;
    //         $lastDriveEndHour = null;
    //         $applyNightCondition = false;
    //         $pauseValidated = false; // Indicateur de pause validée
    //         $lastStopTime = null;

    //         foreach ($movements as $movement) {
    //             $movementStartTime = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
    //             $movementEndTime = new \DateTime($movement['end_date'] . ' ' . $movement['end_hour']);
    //             $immatricule = $truckService->getTruckPlateNumberByImei($movement['imei']);

    //             // Si c'est le premier mouvement, initialiser le début de la journée
    //             if (!$currentDayStart) {
    //                 $currentDayStart = $movementStartTime;
    //             }

    //             // Vérifier si une nouvelle journée commence
    //             // if ($movementStartTime->getTimestamp() - $currentDayStart->getTimestamp() >= $glidingWindow || ($lastStopTime && ($movementStartTime->getTimestamp() - $lastStopTime->getTimestamp()) >= $eightHourRest)) {
    //             //     // Réinitialiser les valeurs pour une nouvelle journée
    //             //     $currentDayStart = $movementStartTime;
    //             //     $totalDriveDuration = 0;
    //             //     $firstDriveStartHour = null;
    //             //     $lastDriveEndHour = null;
    //             //     $applyNightCondition = false;
    //             //     $pauseValidated = false; // Réinitialiser la validation de pause
    //             //     $lastStopTime = null; // Réinitialiser le dernier arrêt
    //             // }

    //             // Cumuler les durées de DRIVE
    //             if ($movement['type'] === 'DRIVE') {
    //                 $driveDuration = $utils->convertTimeToSeconds($movement['duration']);
    //                 $totalDriveDuration += $driveDuration;

    //                 if (!$firstDriveStartHour) {
    //                     $firstDriveStartHour = $movement['start_hour'];
    //                 }
    //                 $lastDriveEndHour = $movement['end_hour'];
    //                 // Vérifier si le mouvement chevauche une période nocturne
    //                 if ($continueService->isNightPeriod($movement['start_hour'], $movement['end_hour'])) {
    //                     $applyNightCondition = true;
    //                 }
    //             }

    //             // Vérifier les arrêts (STOP)
    //             if ($movement['type'] === 'STOP') {
    //                 $stopDuration = $utils->convertTimeToSeconds($movement['duration']);
    //                 $stopDurationThreshold = $applyNightCondition ? 900 : 1200; // 15 minutes (nuit) ou 20 minutes (jour)

    //                 // Valider la pause si elle est longue
    //                 if ($stopDuration >= $stopDurationThreshold) {
    //                     $pauseValidated = true; // Pause validée
    //                     $lastStopTime = $movementEndTime; // Enregistrer le moment de l'arrêt

    //                     // Vérifier les infractions basées sur les durées de conduite
    //                     $condition = $applyNightCondition ? $nightCondition : $dayCondition;
    //                     if ($totalDriveDuration > $condition) {
    //                         $result[] = self::createInfractionRecord(
    //                             $movement,
    //                             $immatricule,
    //                             $totalDriveDuration,
    //                             $condition,
    //                             $firstDriveStartHour,
    //                             $lastDriveEndHour,
    //                             $currentDayStart,
    //                             $applyNightCondition
    //                         );

    //                         // Journaux de débogage pour l'infraction
    //                         error_log("Infraction enregistrée : Immatricule: $immatricule, Durée totale: $totalDriveDuration, Condition: $condition");
    //                     }

    //                     // Réinitialiser après un arrêt long (pause validée)
    //                     $totalDriveDuration = 0; // Réinitialiser la durée de conduite cumulée
    //                     $applyNightCondition = false; // Réinitialiser l'indicateur de nuit
    //                     $firstDriveStartHour = null; // Réinitialiser l'heure de début du premier trajet
    //                     $lastDriveEndHour = null; // Réinitialiser l'heure de fin du dernier trajet
    //                 }
    //             }
    //         }
            
    //         // Vérifier les infractions après la dernière journée si pause non validée
    //         // if ($totalDriveDuration > 0 && !$pauseValidated) {
    //         //     $condition = $applyNightCondition ? $nightCondition : $dayCondition;
    //         //     if ($totalDriveDuration > $condition) {
    //         //         $result[] = self::createInfractionRecord(
    //         //             end($movements), // Prendre le dernier mouvement
    //         //             $immatricule,
    //         //             $totalDriveDuration,
    //         //             $condition,
    //         //             $firstDriveStartHour,
    //         //             $lastDriveEndHour,
    //         //             $currentDayStart,
    //         //             $applyNightCondition
    //         //         );

    //         //         // Journaux de débogage pour l'infraction
    //         //         error_log("Infraction finale enregistrée : Immatricule: $immatricule, Durée totale: $totalDriveDuration, Condition: $condition");
    //         //     }
    //         // }

    //         return $result;
    //     } catch (Exception $e) {
    //         error_log("Erreur dans le traitement des infractions : " . $e->getMessage());
    //         return [];
    //     }
    // }

    public static function checkForInfraction($movements)
    {
        try {
            $utils = new Utils();
            $continueService = new ConduiteContinueService();
            $truckService = new TruckService();

            // Durées pour les infractions
            $dayDriveLimit = 4 * 3600; // 4 heures de conduite maximale (jour)
            $nightDriveLimit = 2 * 3600; // 2 heures de conduite maximale (nuit)
            $dayPauseDuration = 20 * 60; // 20 minutes (pause jour)
            $nightPauseDuration = 15 * 60; // 15 minutes (pause nuit)
            $glidingWindow = 24 * 3600; // 24 heures glissantes

            $result = [];
            $totalDriveDuration = 0;
            $currentDayStart = null;
            $applyNightCondition = false;
            $pauseValidated = false; // Indicateur de pause validée
            $lastStopTime = null;

            foreach ($movements as $movement) {
                $movementStartTime = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
                $movementEndTime = new \DateTime($movement['end_date'] . ' ' . $movement['end_hour']);
                $immatricule = $truckService->getTruckPlateNumberByImei($movement['imei']);

                // Si c'est le premier mouvement, initialiser le début de la journée
                if (!$currentDayStart) {
                    $currentDayStart = $movementStartTime;
                }

                // Vérifier si une nouvelle journée commence
                if ($movementStartTime->getTimestamp() - $currentDayStart->getTimestamp() >= $glidingWindow) {
                    // Réinitialiser les valeurs pour une nouvelle journée
                    $currentDayStart = $movementStartTime;
                    $totalDriveDuration = 0;
                    $applyNightCondition = false; // Réinitialiser l'indicateur de nuit
                    $pauseValidated = false; // Réinitialiser la validation de pause
                    $lastStopTime = null; // Réinitialiser le dernier arrêt
                }

                // Cumuler les durées de DRIVE
                if ($movement['type'] === 'DRIVE') {
                    $driveDuration = $utils->convertTimeToSeconds($movement['duration']);
                    $totalDriveDuration += $driveDuration;

                    // Vérifier si le mouvement chevauche une période nocturne
                    if ($continueService->isNightPeriod($movementStartTime, $movementEndTime)) {
                        $applyNightCondition = true;
                    }
                }

                // Vérifier les arrêts (STOP)
                if ($movement['type'] === 'STOP') {
                    $stopDuration = $utils->convertTimeToSeconds($movement['duration']);
                    $stopDurationThreshold = $applyNightCondition ? $nightPauseDuration : $dayPauseDuration; // Pause réglementaire

                    // Valider la pause si elle est longue
                    if ($stopDuration >= $stopDurationThreshold) {
                        $pauseValidated = true; // Pause validée
                        $lastStopTime = $movementEndTime; // Enregistrer le moment de l'arrêt

                        // Réinitialiser le cumul de conduite après une pause validée
                        $totalDriveDuration = 0; // Réinitialiser la durée de conduite cumulée
                        $applyNightCondition = false; // Réinitialiser l'indicateur de nuit
                        $currentDayStart = $lastStopTime;
                    }
                }

                // Vérifier si le total dépasse la limite après chaque mouvement
                if ($pauseValidated) {
                    // Vérifier les infractions basées sur les durées de conduite
                    $condition = $applyNightCondition ? $nightDriveLimit : $dayDriveLimit;
                    if ($totalDriveDuration > $condition) {
                        $result[] = self::createInfractionRecord(
                            $movement,
                            $immatricule,
                            $totalDriveDuration,
                            $condition,
                            $movement['start_hour'],
                            $movement['end_hour'],
                            $currentDayStart,
                            $applyNightCondition
                        );

                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            error_log("Erreur dans le traitement des infractions : " . $e->getMessage());
            return [];
        }
    }






    
    public static function createInfractionRecord($movement, $immatricule, $totalDriveDuration, $condition, $firstDriveStartHour, $lastDriveEndHour, $currentDay, $applyNightCondition) {
        $first = new \DateTime($currentDay->format('Y-m-d') . ' ' . $firstDriveStartHour);
        $end = (clone $first)->modify('+' . $totalDriveDuration . ' seconds');
    
        return [
            'calendar_id' => $movement['calendar_id'],
            'imei' => $movement['imei'],
            'rfid' => $movement['rfid'],
            'vehicule' => $immatricule,
            'event' => $applyNightCondition ? "TEMPS DE CONDUITE CONTINUE NUIT" : "TEMPS DE CONDUITE CONTINUE JOUR",
            'distance' => 0,
            'distance_calendar' => 0,
            'odometer' => 0,
            'duree_infraction' => $totalDriveDuration,
            'duree_initial' => $condition,
            'date_debut' => $currentDay->format('Y-m-d'),
            'date_fin' => $end->format('Y-m-d'),
            'heure_debut' => $firstDriveStartHour,
            'heure_fin' => $lastDriveEndHour,
            'point' => ($totalDriveDuration - $condition) / 600,
            'insuffisance' => ($totalDriveDuration - $condition)
        ];
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
                    $allmovements = $mouvementService->getAllMouvementDuringCalendar($calendar->id);
                    $organizeMovements = $mouvementService->organizeMovements($allmovements);  //$calendar->id
                    $infraction = $continueService->checkForInfraction($organizeMovements);
                    if($infraction){
                        $data_infraction = array_merge($data_infraction,$infraction);
                    }
            });

            if (!empty($data_infraction)) {
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