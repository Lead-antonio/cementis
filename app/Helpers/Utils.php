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

    // Antonio
    // GET DRIVES AND STOPS
    public static function getDrivesAndStop($imei_vehicule, $start_date, $end_date){
        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=OBJECT_GET_ROUTE," . $imei_vehicule . "," . $start_date->format('YmdHis') . "," . $end_date->format('YmdHis') . ",20";
    
        try {
            $response = Http::timeout(300)->get($url);
            $data = $response->json();
    
            $drives = isset($data['drives']) && is_array($data['drives']) ? $data['drives'] : null;
            $stops = isset($data['stops']) && is_array($data['stops']) ? $data['stops'] : null;
    
            // Retourner les drives et stops (peuvent être null ou des tableaux vides)
            return [
                'drives' => $drives,
                'stops' => $stops
            ];
        } catch (\Exception $e) {
            // Gérer les erreurs de requête HTTP
            // Vous pouvez enregistrer le message d'erreur ou retourner des valeurs par défaut
            return [
                'drives' => null,
                'stops' => null
            ];
        }

    }

    // Antonio
    // SAVE DRIVES AND STOPS
    public static function saveDriveAndStop(){
        $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
        $existingTrucks = Vehicule::all(['nom', 'imei']);
        $truckData = $existingTrucks->pluck('imei', 'nom');
        $truckNames = $truckData->keys();
        
        $calendars = ImportExcel::whereIn('camion', $truckNames)
        ->where('import_calendar_id', $lastmonth)
        ->chunk(10, function ($calendars) use ($truckData) {
            $calendars->each(function ($calendar) use ($truckData) {
                $calendar->imei = $truckData->get(trim($calendar->camion));
                $calendar_start_date = Carbon::parse($calendar->date_debut);
                $calendar_end_date = $calendar->date_fin ? Carbon::parse($calendar->date_fin) : null;

                if ($calendar_end_date === null) {
                    $dureeEnHeures = floatval($calendar->delais_route);
                    if ($dureeEnHeures <= 1) {
                        $calendar_end_date = $calendar_start_date->copy()->endOfDay();
                    } else {
                        $dureeEnJours = ceil($dureeEnHeures / 24);
                        $calendar_end_date = $calendar_start_date->copy()->addDays($dureeEnJours);
                    }
                }
                $drive_and_stops = Utils::getDrivesAndStop($calendar->imei, $calendar_start_date, $calendar_end_date);

                if (!empty($drive_and_stops['drives'])) {
                    foreach ($drive_and_stops['drives'] as $drive) {
                        DB::table('movement')->insert([
                            'calendar_id' => $calendar->id,
                            'start_date' => Carbon::parse($drive['dt_start']),
                            'start_hour' => Carbon::parse($drive['dt_start'])->format('H:i:s'),
                            'end_date' => Carbon::parse($drive['dt_end']),
                            'end_hour' => Carbon::parse($drive['dt_end'])->format('H:i:s'),
                            'duration' => Utils::convertDurationToTime($drive['duration']),
                            'type' => 'DRIVE',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
    
                if (!empty($drive_and_stops['stops'])) {
                    foreach ($drive_and_stops['stops'] as $stop) {
                        DB::table('movement')->insert([
                            'calendar_id' => $calendar->id,
                            'start_date' => Carbon::parse($stop['dt_start']),
                            'start_hour' => Carbon::parse($stop['dt_start'])->format('H:i:s'),
                            'end_date' => Carbon::parse($stop['dt_end']),
                            'end_hour' => Carbon::parse($stop['dt_end'])->format('H:i:s'),
                            'duration' => Utils::convertDurationToTime($stop['duration']),
                            'type' => 'STOP',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            });  

            
        });
    }
}