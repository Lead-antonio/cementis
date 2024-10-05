<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use App\Models\ImportExcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\CalendarService;
use App\Helpers\Utils;

class ReposJournalierService
{
    /**
     * Antonio
     * Vérification si il y a un TEMPS DE CONDUITE JOUR ou NUIT.
     *
     */
    public static function checkForInfractionReposJournalier($movement)
    {
        try {
            // Vérification des données nécessaires
            if (!isset($movement['duration'], $movement['start_hour'], $movement['calendar_id'], $movement['imei'], $movement['rfid'])) {
                throw new Exception("Données manquantes dans le mouvement.");
            }

            $utils = new Utils();
            $continueService = new ConduiteContinueService();
            $truckService = new TruckService();

            // Conditions de repos en secondes
            $conditions = [
                'day' => 8 * 3600, // 8 heures (jour)
                'night' => 10 * 3600, // 10 heures (nuit)
            ];

            $result = [];
            $immatricule = $truckService->getTruckPlateNumberByImei($movement['imei']);
            // Convertir la durée du STOP en secondes
            $stopDuration = $utils->convertTimeToSeconds($movement['duration']);

            // Vérifier si l'heure de début du STOP est dans la plage nuit
            $isNightTime = $utils->isBetweenNightPeriod($movement['start_hour']);

            // Déterminer la condition à appliquer (nuit ou jour)
            $condition = $isNightTime ? $conditions['night'] : $conditions['day'];

            // Vérifier si la durée du STOP est inférieure à la condition requise
            if ($stopDuration < $condition) {
                $event = "Temps de repos minimum après une journée de travail";

                $result = [
                    'calendar_id' => $movement['calendar_id'],
                    'imei' => $movement['imei'],
                    'rfid' => $movement['rfid'],
                    'event' => $event,
                    'vehicule' => $immatricule,
                    'distance' => 0,
                    'distance_calendar' => 0,
                    'odometer' => 0,
                    'duree_infraction' => $stopDuration,
                    'duree_initial' => $condition,
                    'date_debut' => $movement['start_date'],
                    'date_fin' => $movement['end_date'],
                    'heure_debut' => $movement['start_hour'],
                    'heure_fin' => $movement['end_hour'],
                    'point' => ($condition - $stopDuration) / 600, // Points calculés
                    'insuffisance' => ($condition - $stopDuration) // Différence en secondes
                ];
            }

            return $result;

        } catch (Exception $e) {
            // Enregistre l'erreur dans les logs
            Log::error("Erreur dans checkForInfractionReposJournalier: " . $e->getMessage());
        }
    }



    /**
     * Antonio
     * Vérification des infractions de conduite continue cumul par rapport à la  période du calendrier.
     *
     */
    public static function checkTempsReposMinInJourneyTravail($console){
        try{
            $lastmonth = DB::table('import_calendar')->latest('id')->value('id');

            $mouvementService = new MovementService();
            $repos_journalier_service = new ReposJournalierService();
            $calendarService = new CalendarService();

            $data_infraction = [];

            $all_journey = $calendarService->getAllJourneyDuringCalendar($console);
            
            $console->withProgressBar($all_journey, function($journey) use ($mouvementService, $repos_journalier_service, &$data_infraction) {
                    $max_stop_movement = $mouvementService->getMaxStopInJourney($journey['start'], $journey['end']);
                    $infraction = $repos_journalier_service->checkForInfractionReposJournalier($max_stop_movement);
                    if (!empty($infraction)) {
                        $data_infraction[] = $infraction;
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