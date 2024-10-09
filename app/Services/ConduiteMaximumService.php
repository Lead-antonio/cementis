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