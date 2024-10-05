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

class ReposHebdoService
{
    
    /**
     * Antonio
     * Vérification des infractions de conduite continue cumul par rapport à la  période du calendrier.
     *
     */
    public static function checkTempsReposHebdoInWeek($console, $start_date, $end_date){
        try{
            $repos_hebdo_service = new ReposHebdoService();
            $calendarService = new CalendarService();

            $data_infraction = [];

            $all_work_weekly = $calendarService->getAllWorkWeekly($start_date, $end_date);

            $console->withProgressBar($all_work_weekly, function($week) use ($repos_hebdo_service, &$data_infraction) {
                    $infraction = $repos_hebdo_service->checkForInfractionReposHebdo($week);
                    if (!empty($infraction)) {
                        $data_infraction[] = $infraction;
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

    public static function checkForInfractionReposHebdo($week)
    {
        try {
            $utils = new Utils();
            // Conditions de repos en secondes
            $condition = 24 * 3600;
            $start_date_time = new \DateTime($week['start']);
            $end_date_time = new \DateTime($week['end']);

            $result = [];
            $max_stop_duration = $utils->convertTimeToSeconds($week['max_stop_duration']);

            // Vérifier si la durée du STOP est inférieure à la condition requise
            if ($max_stop_duration < $condition) {
                $event = "Temps de repos hebdomadaire";

                $result = [
                    'calendar_id' => null,
                    'imei' => $week['imei'],
                    'rfid' => $week['rfid'],
                    'event' => $event,
                    'vehicule' => $week['camion'],
                    'distance' => 0,
                    'distance_calendar' => 0,
                    'odometer' => 0,
                    'duree_infraction' => $max_stop_duration,
                    'duree_initial' => $condition,
                    'date_debut' => $start_date_time->format('Y-m-d'),
                    'date_fin' => $end_date_time->format('Y-m-d'),
                    'heure_debut' => $start_date_time->format('H:i:s'),
                    'heure_fin' => $end_date_time->format('H:i:s'),
                    'point' => ($condition - $max_stop_duration) / 600, // Points calculés
                    'insuffisance' => ($condition - $max_stop_duration) // Différence en secondes
                ];
            }

            return $result;

        } catch (Exception $e) {
            // Enregistre l'erreur dans les logs
            Log::error("Erreur dans checkForInfractionReposJournalier: " . $e->getMessage());
            return $e->getMessage();
        }
    }
}