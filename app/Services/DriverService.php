<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use App\Models\ImportExcel;
use App\Models\Vehicule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DriverService
{
    /**
     * Antonio
     * get distance et RFID des camions dans le calendrier.
     *
     */
    public function checkDistanceAndRfid()
    {
        $apiService = new GeolocalisationService();
        $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
        $existingTrucks = Vehicule::all(['nom', 'imei']);
        $truckData = $existingTrucks->pluck('imei', 'nom');
        $truckNames = $truckData->keys();

        ImportExcel::whereIn('camion', $truckNames)
            ->where('import_calendar_id', $lastmonth)
            ->chunk(10, function ($calendars) use ($truckData, $apiService) {
                $calendars->each(function ($calendar) use ($truckData, $apiService) {
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
                    $api = $apiService->getRfidAndDistanceWithImeiAndPeriod($calendar->imei, $calendar_start_date, $calendar_end_date);
                    $calendar->rfid_chauffeur = $api['rfid'];
                    $calendar->distance = $api['distance'];
                });

                // Mise à jour en batch dans la base de données
                DB::transaction(function () use ($calendars) {
                    foreach ($calendars as $item) {
                        ImportExcel::where('id', $item->id)->update([
                            'distance' => $item->distance,
                            'imei' => $item->imei,
                            'rfid_chauffeur' => $item->rfid_chauffeur,
                        ]);
                    }
                });
            });
    }

}