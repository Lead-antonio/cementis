<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Movement;
use App\Helpers\Utils;
use App\Models\Vehicule;
use App\Models\ImportExcel;

class MovementService
{
    /**
     * Antonio
     * Trier les mouvements par couple DRIVE + STOP.
     * @param console $console
     */
    public static function saveDriveAndStop($console)
    {
        $geoloc_service = new GeolocalisationService();
        $utils = new Utils();
        $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
        $existingTrucks = Vehicule::all(['nom', 'imei']);
        $truckData = $existingTrucks->pluck('imei', 'nom');
        $truckNames = $truckData->keys();

        // Récupération des calendriers
        $calendars = ImportExcel::whereIn('camion', $truckNames)
            ->where('import_calendar_id', $lastmonth)
            ->get();

        // Affichage de la barre de progression
        $console->withProgressBar($calendars, function ($calendar) use ($truckData, $geoloc_service) {
            $calendar->imei = $truckData->get(trim($calendar->camion));
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

            $drive_and_stops = $geoloc_service->getMovementDriveAndStop($calendar->imei, $calendar_start_date, $calendar_end_date);

            if (!empty($drive_and_stops['drives'])) {
                foreach ($drive_and_stops['drives'] as $drive) {
                    $drive_start_date = (new \DateTime($drive['dt_start']))->modify('+3 hours');
                    $drive_end_date = (new \DateTime($drive['dt_end']))->modify('+3 hours');
                    DB::table('movement')->insertOrIgnore([
                        'imei' => $calendar->imei,
                        'rfid' => $drive_and_stops['rfid'],
                        'calendar_id' => $calendar->id,
                        'start_date' => $drive_start_date,
                        'end_date' => $drive_end_date,
                        'start_hour' => $drive_start_date->format('H:i:s'),
                        'end_hour' => $drive_end_date->format('H:i:s'),
                        'duration' => $utils->convertDurationToTime($drive['duration']),
                        'type' => 'DRIVE',
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime(),
                    ]);
                }
            }

            if (!empty($drive_and_stops['stops'])) {
                foreach ($drive_and_stops['stops'] as $stop) {
                    $stop_start_date = (new \DateTime($stop['dt_start']))->modify('+3 hours');
                    $stop_end_date = (new \DateTime($stop['dt_end']))->modify('+3 hours');
                    DB::table('movement')->insertOrIgnore([
                        'imei' => $calendar->imei,
                        'rfid' => $drive_and_stops['rfid'],
                        'calendar_id' => $calendar->id,
                        'start_date' => $stop_start_date,
                        'end_date' => $stop_end_date,
                        'start_hour' => $stop_start_date->format('H:i:s'),
                        'end_hour' => $stop_end_date->format('H:i:s'),
                        'duration' => $utils->convertDurationToTime($stop['duration']),
                        'type' => 'STOP',
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime(),
                    ]);
                }
            }
        });

        $console->info('All movements have been processed.');
    }


    /**
     * Antonio
     * Retourne les mouvements d'un calendrier donné.
     */
    public function getAllMouvementDuringCalendar($calendar_id){
        try {
            // Récupération de mouvements effectuer durant le calendrier
            $movements = Movement::where('calendar_id', $calendar_id)
            ->orderBy('start_date')
            ->orderBy('end_date')
            ->orderBy('start_hour')
            ->get()
            ->toArray();
            // Gestion du cas où aucun point de pénalité n'est trouvé
            return $movements ? $movements : []; // Retourne 0 si pas de pénalité trouvée

        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error("Erreur lors de la récupération des mouvements pendant un calandrier : " . $e->getMessage());
            return 0;
        }
    }

    

    public function getAllMovementByJourney($startDateTime, $endDateTime){
        
        try {
            // Récupération de mouvements effectuer durant le calendrier
            $movements = Movement::whereRaw("CONCAT(start_date, ' ', start_hour) >= ? AND CONCAT(start_date, ' ', start_hour) <= ?", [$startDateTime, $endDateTime])
            ->orderBy('start_date', 'asc')
            ->orderBy('end_date', 'asc')
            ->orderBy('start_hour', 'asc')
            ->get();
            // Gestion du cas où aucun point de pénalité n'est trouvé
            return $movements ? $movements : []; // Retourne 0 si pas de pénalité trouvée

        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error("Erreur lors de la récupération des mouvements pendant un calandrier : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Antonio
     * Retourne les mouvements d'un calendrier donné.
     */
    public function getStopBehindGivingDateAndHour($movements, $targetDate, $targetTime){
        try {
            $closestStop = $movements->filter(function ($movement) use ($targetDate, $targetTime) {
                return $movement->type === 'STOP' && $movement->start_date === $targetDate && (
                    ($movement->start_hour >= $targetTime)
                );
            })
            ->sortBy(function ($movement) {
                return $movement->start_date . ' ' . $movement->start_hour;
            })
            ->first();
        
            return $closestStop;

        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error("Erreur lors de la récupération des mouvements pendant un calandrier : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Antonio
     * Trier les mouvements par couple DRIVE + STOP.
     */
    public function organizeMovements($allmovements){
        try {
            // Trier les mouvements par start_date et start_hour
            $sortedMovements = $allmovements->sortBy(function($movement) {
                return $movement->start_date . ' ' . $movement->end_date . ' ' . $movement->start_hour;
            })->values()->toArray(); // Convertir en tableau après tri
            
            $organizedMovements = [];
            $previousMovement = null;

            foreach ($sortedMovements as $currentMovement) {
                // Si c'est le premier mouvement, on l'ajoute directement
                if (!$previousMovement) {
                    $organizedMovements[] = $currentMovement;
                } else {
                    // Si le précédent mouvement est un DRIVE et que le courant est un STOP qui suit immédiatement
                    if ($previousMovement['type'] === 'DRIVE' && $currentMovement['type'] === 'STOP' &&
                        $previousMovement['end_date'] === $currentMovement['start_date'] &&
                        $previousMovement['end_hour'] === $currentMovement['start_hour']) {
                        // Ajouter le STOP juste après le DRIVE
                        $organizedMovements[] = $currentMovement;
                    } else {
                        // Si ce n'est pas le cas, ajouter le mouvement actuel
                        $organizedMovements[] = $currentMovement;
                    }
                }

                // Mettre à jour le mouvement précédent
                $previousMovement = $currentMovement;
            }

            return $organizedMovements;

        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error("Erreur lors du trie des mouvements par couple DRIVE + STOP : " . $e->getMessage());
            return 0;
        }
    }

}