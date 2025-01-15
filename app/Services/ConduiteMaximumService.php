<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use App\Models\ImportExcel;
use Carbon\Carbon;
use App\Helpers\Utils;
use App\Models\Movement;
use App\Models\Penalite;
use App\Models\Vehicule;
use DateTime;
use Illuminate\Support\Facades\DB;

class ConduiteMaximumService
{
    /**
     * Antonio
     * Vérification si il y a un TEMPS DE CONDUITE  MAXIMUM DANS UNE JOURNEE DE TRAVAIL.
     * Avec règle de jour ou nuit et point selon durée effectué
     */
    // public static function checkForInfractionConduiteMax($movements)
    // {
    //     $utils = new Utils();
    //     $continueService = new ConduiteContinueService();
    //     $truckService = new TruckService();
    //     $totalDriveDuration = 0;
    //     $applyNightCondition = false;
    //     $dayCondition = 3600 * 13; // 13 heures (jour)
    //     $nightCondition = 3600 * 12; // 12 heures (nuit)
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

    //             if($applyNightCondition == true){
    //                 if($totalDriveDuration > $nightCondition ){
    //                     $result[] = [
    //                         'calendar_id' => $movement['calendar_id'],
    //                         'imei' => $movement['imei'],
    //                         'rfid' => $movement['rfid'],
    //                         'vehicule' => $immatricule,
    //                         'event' => "Temps de conduite maximum dans une journée de travail",
    //                         'distance' => 0,
    //                         'distance_calendar' => 0,
    //                         'odometer' => 0,
    //                         'duree_infraction' => ($totalDriveDuration - $nightCondition),
    //                         'duree_initial' => 600,
    //                         'date_debut' => $first_drive_start_date,
    //                         'date_fin' => $last_drive_end_date,
    //                         'heure_debut' => $first_drive_start_hour,
    //                         'heure_fin' => $last_drive_end_hour,
    //                         'point' => ($totalDriveDuration - $nightCondition)  / 600
    //                     ];

    //                     $totalDriveDuration = 0;
    //                     $applyNightCondition = false;
    //                     $first_drive_start_hour = null;
    //                     $last_drive_end_hour = null;
    //                     $first_drive_start_date = null;
    //                     $last_drive_end_date = null;

    //                 }
    //             }
                
    //             if($applyNightCondition == false){

    //                 if($totalDriveDuration > $dayCondition ){
    //                     $result[] = [
    //                         'calendar_id' => $movement['calendar_id'],
    //                         'imei' => $movement['imei'],
    //                         'rfid' => $movement['rfid'],
    //                         'vehicule' => $immatricule,
    //                         'event' => "Temps de conduite maximum dans une journée de travail",
    //                         'distance' => 0,
    //                         'distance_calendar' => 0,
    //                         'odometer' => 0,
    //                         'duree_infraction' => ($totalDriveDuration - $dayCondition),
    //                         'duree_initial' => $dayCondition,
    //                         'date_debut' => $first_drive_start_date,
    //                         'date_fin' => $last_drive_end_date,
    //                         'heure_debut' => $first_drive_start_hour,
    //                         'heure_fin' => $last_drive_end_hour,
    //                         'point' => ($totalDriveDuration - $dayCondition ) / 600
    //                     ];

    //                     $totalDriveDuration = 0;
    //                     $applyNightCondition = false;
    //                     $first_drive_start_hour = null;
    //                     $last_drive_end_hour = null;
    //                     $first_drive_start_date = null;
    //                     $last_drive_end_date = null;

    //                 }
    //             }

    //     }
    //     return $result;
    // } 

    /**
     * jonny
     * Vérification si il y a un TEMPS DE CONDUITE  MAXIMUM DANS UNE JOURNEE DE TRAVAIL.
     * Sans règle de jour ou nuit et à chaque infraction 1 point
     */
    public static function checkForInfractionConduiteMax($movements)
    {
        $utils = new Utils();
        $continueService = new ConduiteContinueService();
        $truckService = new TruckService();
        $penaliteService = new PenaliteService();
        $totalDriveDuration = 0;
        $condition = (13 * 3600) + 600; // 13 heures 10 minutes
        $result = [];
        $infractionFound = false;
        $event = "Temps de conduite maximum dans une journée de travail";

        $immatricule = null;

        // Variables pour date et heure de début et fin du premier et dernier DRIVE de la journée
        $first_drive_start_date = null;
        $last_drive_end_date = null;
        $first_drive_start_hour = null;
        $last_drive_end_hour = null;
        $point = $penaliteService->getPointPenaliteByEventType($event);

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

            if($totalDriveDuration > $condition ){
                $result[] = [
                    'calendar_id' => $movement['calendar_id'],
                    'imei' => $movement['imei'],
                    'rfid' => $movement['rfid'],
                    'vehicule' => $immatricule,
                    'event' => "Temps de conduite maximum dans une journée de travail",
                    'distance' => 0,
                    'distance_calendar' => 0,
                    'odometer' => 0,
                    'duree_infraction' => ($totalDriveDuration - $condition),
                    'duree_initial' => $condition,
                    'date_debut' => $first_drive_start_date,
                    'date_fin' => $last_drive_end_date,
                    'heure_debut' => $first_drive_start_hour,
                    'heure_fin' => $last_drive_end_hour,
                    'point' => $point
                ];

                $totalDriveDuration = 0;
                $first_drive_start_hour = null;
                $last_drive_end_hour = null;
                $first_drive_start_date = null;
                $last_drive_end_date = null;

            }
        }
        return $result;
    } 

    
    /**
     * Antonio
     * Enregistrement des infractions de  TEMPS DE CONDUITE  MAXIMUM DANS UNE JOURNEE DE TRAVAIL.
     * 
     */
    public static function checkTempsConduiteMaximum($console){
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
                ->where('date_fin', '<=', $lastDayTwoMonthsAgo)
                ->orderBy('date_debut', 'asc') // ou 'desc' pour ordre décroissant
                ->get();
            
            
            $data_infraction = [];
            // $data_journeys = [];


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

                    $journeyCount = count($all_journey);
                   
                    $console->withProgressBar($journeyCount, function($progressBar) use ($all_journey,$mouvementService, $continueService, &$data_infraction, $imei) {
                        foreach($all_journey as $journey){
                            $allmovements = $mouvementService->getAllMovementByJourney($imei, $journey['start'], $journey['end']);
                            $infraction = self::checkForInfractionConduiteMax($allmovements);
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
                // dd($data_journeys);
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