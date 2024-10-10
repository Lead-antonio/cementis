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
     * Summary of CheckDriveMaxDayAndNight
     * jonny
     * @param mixed $startDate
     * @param mixed $endDate
     * @return void
     */
    // public function CheckDriveMaxDayAndNight($startDate, $endDate) {
    //     // Définir les périodes de jour et de nuit
    //     $dayStart = Carbon::createFromTime(4, 0, 0);    // 4h00
    //     $dayEnd = Carbon::createFromTime(21, 59, 59);   // 21h59
    //     $nightStart = Carbon::createFromTime(22, 0, 0); // 22h00
    //     $nightEnd = Carbon::createFromTime(3, 59, 59)->addDay(); // 3h59 du jour suivant
    
    //     // Récupérer les mouvements (conduite) pour la période donnée
    //     $movements = Movement::where('type', 'DRIVE')
    //         ->where('start_date', '>=', $startDate)
    //         ->where('end_date', '<=', $endDate)
    //         ->get();
    
    //     // Grouper les mouvements par chauffeur (rfid) et véhicule (imei)
    //     $groupedMovements = $movements->groupBy(function($item) {
    //         return $item->imei . '-' . $item->rfid;
    //     });
    
    //     // Parcourir chaque groupe (un chauffeur et un véhicule)
    //     foreach ($groupedMovements as $group) {
    //         $dayDrivingDuration = 0; // Durée totale de conduite de jour pour ce groupe
    //         $nightDrivingDuration = 0; // Durée totale de conduite de nuit pour ce groupe
    //         $firstDriveStart = null; // Variable pour stocker le début de la première conduite
    
    //         foreach ($group as $movement) {
    //             $startDateTime = Carbon::parse($movement->start_date . ' ' . $movement->start_hour);
    //             $endDateTime = Carbon::parse($movement->end_date . ' ' . $movement->end_hour);
    
    //             // Initialiser le début de la journée de conduite à la première conduite
    //             if (is_null($firstDriveStart)) {
    //                 $firstDriveStart = $startDateTime;
    //             }
    
    //             // Calculer la fin de la journée glissante de 24h
    //             $dayEndWindow = $firstDriveStart->copy()->addHours(24);
    
    //             // Si le mouvement dépasse la période de 24h, on arrête l'analyse pour cette période
    //             if ($endDateTime > $dayEndWindow) {
    //                 break;
    //             }
    
    //             // Séparer en période jour et nuit si chevauchement
    //             if ($startDateTime < $dayEnd && $endDateTime > $dayStart) {
    //                 // Calculer la durée de conduite de jour
    //                 $dayDrivingDuration += $this->calculateDrivingInRange($startDateTime, $endDateTime, $dayStart, $dayEnd);
    //             }
    
    //             if ($startDateTime < $nightEnd && $endDateTime > $nightStart) {
    //                 // Calculer la durée de conduite de nuit
    //                 $nightDrivingDuration += $this->calculateDrivingInRange($startDateTime, $endDateTime, $nightStart, $nightEnd);
    //             }
    //         }
    
    //         // Vérifier les infractions pour conduite de jour
    //         if ($dayDrivingDuration > 13 * 3600) { // 13 heures en secondes
    //             $this->registerInfraction($group[0]->rfid, $group[0]->imei, 'Conduite maximum dans une journée de travail (Jour)', $dayDrivingDuration, $startDate, $group, 13 * 3600);
    //         }
    
    //         // Vérifier les infractions pour conduite de nuit
    //         if ($nightDrivingDuration > 12 * 3600) { // 12 heures en secondes
    //             $this->registerInfraction($group[0]->rfid, $group[0]->imei, 'Conduite maximum dans une journée de travail (Nuit)', $nightDrivingDuration, $startDate, $group, 12 * 3600);
    //         }
    //     }
    // }

    

    public static function checkTempsConduiteMaximum($console){
        try{
            $mouvementService = new MovementService();
            $continueService = new ConduiteContinueService();
            $calendarService = new CalendarService();
            $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
            $existingTrucks = Vehicule::all(['nom', 'imei']);
            $truckData = $existingTrucks->pluck('imei', 'nom');
            $truckNames = $truckData->keys();

            // Récupération des calendriers
            $calendars = ImportExcel::whereIn('camion', $truckNames)
                ->where('import_calendar_id', $lastmonth)
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
                            $infraction = $continueService->checkForInfractionTemps($allmovements);
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
                // dd($data_infraction);
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