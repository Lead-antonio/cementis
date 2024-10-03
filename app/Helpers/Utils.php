<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\ImportExcel;
use App\Models\Vehicule;


class Utils
{
    /**
     * Antonio
     * convert duration (1 d 20 h 30 min 23 s) to time (26:30:23)
     * @param string $durationString
     * return HH:MM:SS
     */
    public static function convertDurationToTime($duration) {
        // Initialiser les valeurs par défaut
        $days = 0;
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
    
        // Convertir la durée en tableau basé sur les espaces
        $timeParts = explode(' ', $duration);
    
        // Parcourir les parties de la durée et attribuer aux bonnes variables
        foreach ($timeParts as $index => $part) {
            if (strpos($part, 'd') !== false) {
                $days = intval($timeParts[$index - 1]);
            } elseif (strpos($part, 'h') !== false) {
                $hours = intval($timeParts[$index - 1]);
            } elseif (strpos($part, 'min') !== false) {
                $minutes = intval($timeParts[$index - 1]);
            } elseif (strpos($part, 's') !== false) {
                $seconds = intval($timeParts[$index - 1]);
            }
        }
    
        // Ajouter les jours convertis en heures (1 jour = 24 heures)
        $hours += $days * 24;
    
        // Retourner la durée au format H:i:s
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
    

    /**
     * Antonio
     * Converte duration to time HH:MM:SS.
     * @param string $durationString
     * return HH:MM:SS
     */
    public static function convertToTimeFormat($durationString)
    {
        // Initialiser les valeurs par défaut
        $hours = "00";
        $minutes = "00";
        $seconds = "00";

        // Rechercher les heures, minutes et secondes avec une expression régulière flexible
        if (preg_match('/(\d+)\s*h/', $durationString, $hoursMatch)) {
            $hours = str_pad($hoursMatch[1], 2, "0", STR_PAD_LEFT);
        }
        if (preg_match('/(\d+)\s*min/', $durationString, $minutesMatch)) {
            $minutes = str_pad($minutesMatch[1], 2, "0", STR_PAD_LEFT);
        }
        if (preg_match('/(\d+)\s*s/', $durationString, $secondsMatch)) {
            $seconds = str_pad($secondsMatch[1], 2, "0", STR_PAD_LEFT);
        }

        // Retourner le format "HH:MM:SS"
        return "$hours:$minutes:$seconds";
    }


    /**
     * Antonio
     * Convert time to duration.
     * @param string $time
     * return 2098
     */
    public static function convertTimeToSeconds($time)
    {
        // Séparer les heures, les minutes et les secondes
        list($hours, $minutes, $seconds) = explode(':', $time);

        // Calculer le total en secondes
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }


    /**
     * Antonio
     * Vérification du plage de nuit.
     * @param string $start_hour
     * @param string $end_hour
     * return false | true
     */
    public static function isNightPeriod($startHour, $endHour)
    {
        // Convertir les heures en objets DateTime pour faciliter la comparaison
        $startTime = new \DateTime($startHour);
        $endTime = new \DateTime($endHour);
        
        // Définir les périodes de jour et de nuit
        $dayStart = new \DateTime('04:00:00');
        $dayEnd = new \DateTime('22:00:00');
        
        // Cas 1 : Si le début et la fin sont dans la même journée (ne traverse pas minuit)
        if ($startTime >= $dayStart && $endTime <= $dayEnd) {
            return false; // C'est pendant la journée
        }
        
        // Cas 2 : Si la plage horaire traverse minuit
        if ($startTime > $endTime) {
            // Ceci gère les cas où la période traverse minuit (par exemple, de 18h00 à 02h00)
            return true;
        }
        
        // Cas 3 : Si la plage est entièrement pendant la période de nuit
        if ($startTime >= $dayEnd || $endTime <= $dayStart) {
            return true; // C'est pendant la nuit
        }

        // Par défaut, retourner vrai si cela tombe en dehors de la période de jour (cas particulier)
        return true;
    }

    /**
     * Antonio
     * Vérification du plage de nuit.
     * @param string $start_hour
     * return false | true
     */
    public function isBetweenNightPeriod($startHour)
    {
        $applyNightCondition = false;

        $startTime = new \DateTime($startHour);
        // Plage de nuit
        $nightStart = new \DateTime("22:00:00");
        $nightEnd = new \DateTime("04:00:00");

        if ($startTime >= $nightStart || $startTime <= $nightEnd) {
            // L'heure est dans la plage de nuit
            $applyNightCondition = true;
        }
        
        return $applyNightCondition;
    }


}