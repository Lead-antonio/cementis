<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\ImportExcel;
use App\Models\Vehicule;


class Utils
{
    // Antonio
    // convert duration to time
    public static function convertDurationToTime($duration) {
        // Initialiser les valeurs par défaut
        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        // Convertir la durée en tableau basé sur les espaces
        $timeParts = explode(' ', $duration);

        // Parcourir les parties de la durée et attribuer aux bonnes variables
        foreach ($timeParts as $index => $part) {
            if (strpos($part, 'h') !== false) {
                $hours = intval($timeParts[$index - 1]);
            } elseif (strpos($part, 'min') !== false) {
                $minutes = intval($timeParts[$index - 1]);
            } elseif (strpos($part, 's') !== false) {
                $seconds = intval($timeParts[$index - 1]);
            }
        }

        // Retourner la durée au format H:i:s
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

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


    public static function convertTimeToSeconds($time)
    {
        // Séparer les heures, les minutes et les secondes
        list($hours, $minutes, $seconds) = explode(':', $time);

        // Calculer le total en secondes
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    // Antonio
    // GET DRIVES AND STOPS
    public static function getDrivesAndStop($imei_vehicule, $start_date, $end_date){
        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=OBJECT_GET_ROUTE," . $imei_vehicule . "," . $start_date->format('YmdHis') . "," . $end_date->format('YmdHis') . ",1";
        
        try {
            $response = Http::timeout(300)->get($url);
            $data = $response->json();
            $drives = isset($data['drives']) && is_array($data['drives']) ? $data['drives'] : null;
            $stops = isset($data['stops']) && is_array($data['stops']) ? $data['stops'] : null;
            $rfid = isset($data['route']) && is_array($data['route']) ? $data['route'][0][6]['rfid'] : null;
    
            // Retourner les drives et stops (peuvent être null ou des tableaux vides)
            return [
                'rfid' => $rfid,
                'drives' => $drives,
                'stops' => $stops
            ];
        } catch (\Exception $e) {
            // Gérer les erreurs de requête HTTP
            // Vous pouvez enregistrer le message d'erreur ou retourner des valeurs par défaut
            return [
                'rfid' => null,
                'drives' => null,
                'stops' => null
            ];
        }

    }

    // Antonio
    // SAVE DRIVES AND STOPS
    // public static function saveDriveAndStop($console)
    // {
    //     $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
    //     $existingTrucks = Vehicule::all(['nom', 'imei']);
    //     $truckData = $existingTrucks->pluck('imei', 'nom');
    //     $truckNames = $truckData->keys();

    //     // Récupération des calendriers
    //     $calendars = ImportExcel::whereIn('camion', $truckNames)
    //         ->where('import_calendar_id', $lastmonth)
    //         ->get();

    //     // Affichage de la barre de progression
    //     $console->withProgressBar($calendars, function ($calendar) use ($truckData) {
    //         $calendar->imei = $truckData->get(trim($calendar->camion));
    //         $calendar_start_date = Carbon::parse($calendar->date_debut);
    //         $calendar_end_date = $calendar->date_fin ? Carbon::parse($calendar->date_fin) : null;

    //         if ($calendar_end_date === null) {
    //             $dureeEnHeures = floatval($calendar->delais_route);
    //             if ($dureeEnHeures <= 1) {
    //                 $calendar_end_date = $calendar_start_date->copy()->endOfDay();
    //             } else {
    //                 $dureeEnJours = ceil($dureeEnHeures / 24);
    //                 $calendar_end_date = $calendar_start_date->copy()->addDays($dureeEnJours);
    //             }
    //         }

    //         $drive_and_stops = Utils::getDrivesAndStop($calendar->imei, $calendar_start_date, $calendar_end_date);
          
    //         if (!empty($drive_and_stops['drives'])) {
    //             foreach ($drive_and_stops['drives'] as $drive) {
    //                 DB::table('movement')->insertOrIgnore([
    //                     'imei' => $calendar->imei,
    //                     'rfid' => $drive_and_stops['rfid'],
    //                     'calendar_id' => $calendar->id,
    //                     'start_date' => Carbon::parse($drive['dt_start']),
    //                     'start_hour' => Carbon::parse($drive['dt_start'])->format('H:i:s'),
    //                     'end_date' => Carbon::parse($drive['dt_end']),
    //                     'end_hour' => Carbon::parse($drive['dt_end'])->format('H:i:s'),
    //                     'duration' => Utils::convertToTimeFormat($drive['duration']),
    //                     'type' => 'DRIVE',
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ]);
    //             }
    //         }

    //         if (!empty($drive_and_stops['stops'])) {
    //             foreach ($drive_and_stops['stops'] as $stop) {
    //                 DB::table('movement')->insertOrIgnore([
    //                     'imei' => $calendar->imei,
    //                     'rfid' => $drive_and_stops['rfid'],
    //                     'calendar_id' => $calendar->id,
    //                     'start_date' => Carbon::parse($stop['dt_start']),
    //                     'start_hour' => Carbon::parse($stop['dt_start'])->format('H:i:s'),
    //                     'end_date' => Carbon::parse($stop['dt_end']),
    //                     'end_hour' => Carbon::parse($stop['dt_end'])->format('H:i:s'),
    //                     'duration' => Utils::convertDurationToTime($stop['duration']),
    //                     'type' => 'STOP',
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ]);
    //             }
    //         }
    //     });

    //     $console->info('All movements have been processed.');
    // }
    public static function saveDriveAndStop($console)
    {
        $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
        $existingTrucks = Vehicule::all(['nom', 'imei']);
        $truckData = $existingTrucks->pluck('imei', 'nom');
        $truckNames = $truckData->keys();

        // Récupération des calendriers
        $calendars = ImportExcel::whereIn('camion', $truckNames)
            ->where('import_calendar_id', $lastmonth)
            ->get();

        // Affichage de la barre de progression
        $console->withProgressBar($calendars, function ($calendar) use ($truckData) {
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

            $drive_and_stops = Utils::getDrivesAndStop($calendar->imei, $calendar_start_date, $calendar_end_date);

            if (!empty($drive_and_stops['drives'])) {
                foreach ($drive_and_stops['drives'] as $drive) {
                    DB::table('movement')->insertOrIgnore([
                        'imei' => $calendar->imei,
                        'rfid' => $drive_and_stops['rfid'],
                        'calendar_id' => $calendar->id,
                        'start_date' => new \DateTime($drive['dt_start']),
                        'end_date' => new \DateTime($drive['dt_end']),
                        'start_hour' => (new \DateTime($drive['dt_start']))->modify('+3 hours')->format('H:i:s'),
                        'end_hour' => (new \DateTime($drive['dt_end']))->modify('+3 hours')->format('H:i:s'),
                        'duration' => Utils::convertToTimeFormat($drive['duration']),
                        'type' => 'DRIVE',
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime(),
                    ]);
                }
            }

            if (!empty($drive_and_stops['stops'])) {
                foreach ($drive_and_stops['stops'] as $stop) {
                    DB::table('movement')->insertOrIgnore([
                        'imei' => $calendar->imei,
                        'rfid' => $drive_and_stops['rfid'],
                        'calendar_id' => $calendar->id,
                        'start_date' => new \DateTime($stop['dt_start']),
                        'end_date' => new \DateTime($stop['dt_end']),
                        'start_hour' => (new \DateTime($stop['dt_start']))->modify('+3 hours')->format('H:i:s'),
                        'end_hour' => (new \DateTime($stop['dt_end']))->modify('+3 hours')->format('H:i:s'),
                        'duration' => Utils::convertDurationToTime($stop['duration']),
                        'type' => 'STOP',
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime(),
                    ]);
                }
            }
        });

        $console->info('All movements have been processed.');
    }


}