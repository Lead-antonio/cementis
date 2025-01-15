<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Event;
use App\Models\Penalite;

class OverSpeedService{


    public function CheckOverSpeed($startDate, $endDate) {
        // Types d'infractions à vérifier
        $eventTypes = [
            'Excès de vitesse en agglomération', 
            'Excès de vitesse hors agglomération', 
            'Survitesse excessive',
            'Survitesse sur la piste de Tritriva',
            'Survitesse sur la piste d\'Ibity',
        ];
    
        // Boucle sur chaque type d'événement
        foreach ($eventTypes as $eventType) {
            // Récupérer les événements pour un type donné
            $records = DB::table('event')
                ->select('imei', 'chauffeur', 'vehicule', 'type', 'odometer', 'vitesse', 'latitude', 'longitude', 'date')
                ->where('type', $eventType)  // Filtre par type d'événement
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'ASC')
                ->get();
    
            // Grouper les événements par imei, chauffeur et véhicule
            $groupedRecords = $records->groupBy(function($record) {
                return $record->imei . '_' . $record->chauffeur . '_' . $record->vehicule;
            });
    
            foreach ($groupedRecords as $groupKey => $group) {
                $eventIntervals = [];
                $currentInterval = [];
                $prevRecord = null;
        
                foreach ($group as $record) {
                    if ($prevRecord !== null) {
                        $prevDateTime = Carbon::parse($prevRecord->date);
                        $currentDateTime = Carbon::parse($record->date);
                        $timeDiff = $prevDateTime->diffInMinutes($currentDateTime);
        
                        // Vérifier si l'événement actuel est dans un intervalle de moins de 5 minutes
                        if ($timeDiff <= 5) {
                            $currentInterval[] = $record;
                        } else {
                            if (count($currentInterval) > 1) {
                                $this->saveInfraction($currentInterval); // Sauvegarder l'infraction
                            }
                            // Commencer un nouvel intervalle
                            $currentInterval = [$record];
                        }
                    } else {
                        $currentInterval[] = $record;
                    }
                    $prevRecord = $record;
                }
        
                // Ajouter le dernier intervalle s'il y en a un
                if (count($currentInterval) > 1) {
                    $this->saveInfraction($currentInterval); // Sauvegarder l'infraction
                }
            }
        }
        
        return "Infractions détectées et sauvegardées.";
    }    
    
    /**
     * Antonio
     * Vérification si il y a OVERSPEED.
     * Avec calcul de point est calculer selon pluie 
     */
    // private function saveInfraction($interval) {
    //     $penaliteService = new PenaliteService(); 
    //     // Calculer la durée totale de l'infraction
    //     $firstEvent = $interval[0];
    //     $lastEvent = end($interval);
        
    //     $startDateTime = Carbon::parse($firstEvent->date);
    //     $endDateTime = Carbon::parse($lastEvent->date);
    //     $dureeSeconds = $startDateTime->diffInSeconds($endDateTime);
    //     $dureeMinutes = ceil($dureeSeconds / 60);
    
    //     // Calcul des points selon la règle de trois : 60s = 1 point
    //     $penalite = $penaliteService->getPointPenaliteByEventType($firstEvent->type);
    //     $eventType = trim($firstEvent->type);
    //     $penalite_event = Penalite::where('event',$eventType)->first();


    //     $totalPoints = ($dureeSeconds * $penalite ) / $penalite_event->param;

    
    //     // Si le total des points est égal à 0, ne pas sauvegarder l'infraction
    //     if ($totalPoints <= 0) {
    //         return;
    //     }
    
    //     // Sauvegarder dans la table "infractions"
    //     DB::table('infraction')->insert([
    //         'imei' => $firstEvent->imei,
    //         'rfid' => $firstEvent->chauffeur, // Ajouter si rfid = chauffeur
    //         'vehicule' => $firstEvent->vehicule,
    //         'calendar_id' => null, // Remplacer si nécessaire
    //         'event' => $firstEvent->type, // Insérer le type d'événement ici
    //         'distance' => 0, // Peut-être calculer si applicable
    //         'distance_calendar' => null, // Remplacer si nécessaire
    //         'odometer' => $lastEvent->odometer,
    //         'duree_infraction' => $dureeSeconds,
    //         'duree_initial' => $penalite_event->param,
    //         'date_debut' => $startDateTime->toDateString(),
    //         'date_fin' => $endDateTime->toDateString(),
    //         'heure_debut' => $startDateTime->toTimeString(),
    //         'heure_fin' => $endDateTime->toTimeString(),
    //         'gps_debut' => $firstEvent->latitude . ',' . $firstEvent->longitude,
    //         'gps_fin' => $lastEvent->latitude . ',' . $lastEvent->longitude,
    //         'point' => $totalPoints,
    //         'insuffisance' => 0, // Peut être modifié si applicable
    //     ]);
    // }

    /**
     * Antonio
     * Vérification si il y a OVERSPEED.
     * Avec calcul de point est égale à chaque alert
     */
    private function saveInfraction($interval) {
        $penaliteService = new PenaliteService(); 
        // Calculer la durée totale de l'infraction
        $firstEvent = $interval[0];
        $lastEvent = end($interval);
        
        $startDateTime = Carbon::parse($firstEvent->date);
        $endDateTime = Carbon::parse($lastEvent->date);
        $dureeSeconds = $startDateTime->diffInSeconds($endDateTime);
        $dureeMinutes = ceil($dureeSeconds / 60);
    
        // Calcul des points selon la règle de trois : 60s = 1 point
        // $penalite = $penaliteService->getPointPenaliteByEventType($firstEvent->type);
        $eventType = trim($firstEvent->type);
        $penalite_event = Penalite::where('event',$eventType)->first();
        
        // $totalPoints = ($dureeSeconds * $penalite ) / $penalite_event->param;

        // 1 point de pénalité pour l'infraction 
        $totalPoints = 1;

    
        // Si le total des points est égal à 0, ne pas sauvegarder l'infraction
        if ($totalPoints <= 0) {
            return;
        }
    
        // Sauvegarder dans la table "infractions"
        DB::table('infraction')->insert([
            'imei' => $firstEvent->imei,
            'rfid' => $firstEvent->chauffeur, // Ajouter si rfid = chauffeur
            'vehicule' => $firstEvent->vehicule,
            'calendar_id' => null, // Remplacer si nécessaire
            'event' => $firstEvent->type, // Insérer le type d'événement ici
            'distance' => 0, // Peut-être calculer si applicable
            'distance_calendar' => null, // Remplacer si nécessaire
            'odometer' => $lastEvent->odometer,
            'duree_infraction' => $dureeSeconds,
            'duree_initial' => $penalite_event->param,
            'date_debut' => $startDateTime->toDateString(),
            'date_fin' => $endDateTime->toDateString(),
            'heure_debut' => $startDateTime->toTimeString(),
            'heure_fin' => $endDateTime->toTimeString(),
            'gps_debut' => $firstEvent->latitude . ',' . $firstEvent->longitude,
            'gps_fin' => $lastEvent->latitude . ',' . $lastEvent->longitude,
            'point' => $totalPoints,
            'insuffisance' => 0, // Peut être modifié si applicable
        ]);
    }

}