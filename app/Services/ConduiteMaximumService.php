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
    public function CheckDriveMaxDayAndNight($startDate, $endDate) {
        // Définir les périodes de jour et de nuit
        $dayStart = Carbon::createFromTime(4, 0, 0);    // 4h00
        $dayEnd = Carbon::createFromTime(21, 59, 59);   // 21h59
        $nightStart = Carbon::createFromTime(22, 0, 0); // 22h00
        $nightEnd = Carbon::createFromTime(3, 59, 59)->addDay(); // 3h59 du jour suivant
    
        // Récupérer les mouvements du chauffeur et du véhicule pour la date donnée
        $movements = Movement::where('type', 'DRIVE')
            ->where('start_date', '>=' ,$startDate)
            ->where('end_date', '<=' ,$endDate)
            ->get();
    
        $dayDrivingDuration = 0; // Durée totale de conduite de jour
        $nightDrivingDuration = 0; // Durée totale de conduite de nuit
        $firstDriveStart = null; // Variable pour stocker le début de la première conduite
    
        foreach ($movements as $movement) {
            $startDateTime = Carbon::parse($movement->start_date . ' ' . $movement->start_hour);
            $endDateTime = Carbon::parse($movement->end_date . ' ' . $movement->end_hour);
    
            // Initialiser le début de la journée de conduite à la première conduite
            if (is_null($firstDriveStart)) {
                $firstDriveStart = $startDateTime;
            }
    
            // Calculer la fin de la journée glissante de 24h
            $dayEndWindow = $firstDriveStart->copy()->addHours(24);
    
            // Si le mouvement dépasse la période de 24h, on arrête l'analyse pour cette période
            if ($endDateTime > $dayEndWindow) {
                break;
            }
    
            // Séparer en période jour et nuit si chevauchement
            if ($startDateTime < $dayEnd && $endDateTime > $dayStart) {
                // Calculer la durée de conduite de jour
                $dayDrivingDuration += $this->calculateDrivingInRange($startDateTime, $endDateTime, $dayStart, $dayEnd);
            }
    
            if ($startDateTime < $nightEnd && $endDateTime > $nightStart) {
                // Calculer la durée de conduite de nuit
                $nightDrivingDuration += $this->calculateDrivingInRange($startDateTime, $endDateTime, $nightStart, $nightEnd);
            }
        }
    
        // Vérifier les infractions pour conduite de jour
        if ($dayDrivingDuration > 13 * 3600) { // 13 heures en secondes
            $this->registerInfraction($movement->rfid, $movement->imei, 'Conduite maximum dans une journée de travail', $dayDrivingDuration, $startDate, $movements, 13 * 3600);
        }
    
        // Vérifier les infractions pour conduite de nuit
        if ($nightDrivingDuration > 12 * 3600) { // 12 heures en secondes
            $this->registerInfraction($movement->rfid, $movement->imei, 'Conduite maximum dans une journée de travail', $nightDrivingDuration, $startDate, $movements, 12 * 3600);
        }
    }
    

    
    /**
     * Fonction pour calculer la durée de conduite dans une plage horaire spécifique
     * jonny
     * @param mixed $start
     * @param mixed $end
     * @param mixed $rangeStart
     * @param mixed $rangeEnd
     * @return mixed
     */
    private function calculateDrivingInRange($start, $end, $rangeStart, $rangeEnd) {
        $effectiveStart = max($start, $rangeStart);
        $effectiveEnd = min($end, $rangeEnd);
    
        if ($effectiveStart > $effectiveEnd) {
            return 0;
        }
    
        return $effectiveEnd->diffInSeconds($effectiveStart);
    }
    
    /**
     * Fonction pour enregistrer une infraction
     * jonny
     * @param mixed $driverId
     * @param mixed $vehicleId
     * @param mixed $eventType
     * @param mixed $duration
     * @param mixed $date
     * @param mixed $movements
     * @param mixed $maxDuration
     * @return void
     */
    private function registerInfraction($driverId, $vehicleId, $eventType, $duration, $date, $movements, $maxDuration) {
        // Calcul de l'excès de durée
        $excessDuration = $duration - $maxDuration; // Durée au-delà de la limite (soit 13h ou 12h)
        $vehicule = Vehicule::where('imei',$vehicleId)->first();
        // Calcul des points basés sur la règle 600s -> 1 point
        $penalite = Penalite::where('event','Temps de conduite maximum dans une journée de travail')->first();

        // $points = (int)($excessDuration / 600); // Convertir en points
        $points = ($excessDuration *  $penalite->point_penalite) / $penalite->param; // Convertir en points
    
        foreach ($movements as $movement) {
            Infraction::create([
                'rfid' => $driverId,
                'imei' => $vehicleId,
                'vehicule' =>  $vehicule->nom,
                'calendar_id' => $movement->calendar_id,
                'event' => $penalite->event,
                'duree_infraction' => $excessDuration, // Durée d'infraction = excès
                'date_debut' => $movement->start_date,
                'date_fin' => $movement->end_date,
                'heure_debut' => $movement->start_hour,
                'heure_fin' => $movement->end_hour,
                'point' => $points, // Enregistrer les points calculés
                'duree_initial' => $penalite->param,
                'insuffisance' => 0, // Ajuster si nécessaire
            ]);
        }
    }
    
    
    
    
    
}