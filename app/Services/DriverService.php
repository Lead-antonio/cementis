<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use App\Models\ImportExcel;
use App\Models\Vehicule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class DriverService
{
    /**
     * Antonio
     * get distance et RFID des camions dans le calendrier.
     *
     */
    // public function checkDistanceAndRfid()
    // {
    //     $apiService = new GeolocalisationService();
    //     $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
    //     $existingTrucks = Vehicule::all(['nom', 'imei']);
    //     $truckData = $existingTrucks->pluck('imei', 'nom');
    //     $truckNames = $truckData->keys();

    //     // ImportExcel::whereIn('camion', $truckNames)
    //     // ->where('import_calendar_id', $lastmonth)
    //     // ->chunk(10, function ($calendars) use ($truckData, $apiService) {
    //     //     $calendars->each(function ($calendar) use ($truckData, $apiService) {
    //     //         $calendar->imei = $truckData->get(trim($calendar->camion));
    //     //         $calendar_start_date = Carbon::parse($calendar->date_debut);
    //     //         dd($calendar_start_date);
    //     //         $calendar_end_date = $calendar->date_fin ? Carbon::parse($calendar->date_fin) : null;

    //     //         if ($calendar_end_date === null) {
    //     //             $dureeEnHeures = floatval($calendar->delais_route);
    //     //             if ($dureeEnHeures <= 1) {
    //     //                 $calendar_end_date = $calendar_start_date->copy()->endOfDay();
    //     //             } else {
    //     //                 $dureeEnJours = ceil($dureeEnHeures / 24);
    //     //                 $calendar_end_date = $calendar_start_date->copy()->addDays($dureeEnJours);
    //     //             }
    //     //         }
    //     //         $api = $apiService->getRfidAndDistanceWithImeiAndPeriod($calendar->imei, $calendar_start_date, $calendar_end_date);
    //     //         $calendar->rfid_chauffeur = $api['rfid'];
    //     //         $calendar->distance = $api['distance'];
    //     //     });

    //     //     // Mise à jour en batch dans la base de données
    //     //     DB::transaction(function () use ($calendars) {
    //     //         foreach ($calendars as $item) {
    //     //             ImportExcel::where('id', $item->id)->update([
    //     //                 'distance' => $item->distance,
    //     //                 'imei' => $item->imei,
    //     //                 'rfid_chauffeur' => $item->rfid_chauffeur,
    //     //             ]);
    //     //         }
    //     //     });
    //     // });
    //     ImportExcel::whereIn('camion', $truckNames)
    //     ->where('import_calendar_id', $lastmonth)
    //     ->chunk(10, function ($calendars) use ($truckData, $apiService) {
    //         $calendars->each(function ($calendar) use ($truckData, $apiService) {
    //             // Assigner IMEI
    //             $calendar->imei = $truckData->get(trim($calendar->camion));

    //             // Convertir date_debut en \DateTime
    //             $calendar_start_date = new \DateTime($calendar->date_debut);
    //             // dd($calendar_start_date); // Pour visualiser la date

    //             // Convertir date_fin en \DateTime ou laisser null si la date est vide
    //             $calendar_end_date = $calendar->date_fin ? new \DateTime($calendar->date_fin) : null;

    //             // Si date_fin est nulle, calculer en fonction de la durée de route
    //             if ($calendar_end_date === null) {
    //                 $dureeEnHeures = floatval($calendar->delais_route);
    //                 if ($dureeEnHeures <= 1) {
    //                     // Fin de journée
    //                     $calendar_end_date = (clone $calendar_start_date)->setTime(23, 59, 59); // Fin de journée
    //                 } else {
    //                     // Ajouter des jours en fonction de la durée
    //                     $dureeEnJours = ceil($dureeEnHeures / 24);
    //                     $calendar_end_date = (clone $calendar_start_date)->modify("+$dureeEnJours days");
    //                 }
    //             }

    //             // Appeler l'API avec les objets \DateTime
    //             $api = $apiService->getRfidAndDistanceWithImeiAndPeriod($calendar->imei, $calendar_start_date, $calendar_end_date);

    //             // Stocker les résultats dans les champs appropriés
    //             $calendar->rfid_chauffeur = $api['rfid'];
    //             $calendar->distance = $api['distance'];
    //         });

    //         // Mise à jour en batch dans la base de données
    //         DB::transaction(function () use ($calendars) {
    //             foreach ($calendars as $item) {
    //                 ImportExcel::where('id', $item->id)->update([
    //                     'distance' => $item->distance,
    //                     'imei' => $item->imei,
    //                     'rfid_chauffeur' => $item->rfid_chauffeur,
    //                 ]);
    //             }
    //         });
    //     });

    // }
    // public function checkDistanceAndRfid($console)
    // {
    //     $apiService = new GeolocalisationService();
    //     $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
    //     $existingTrucks = Vehicule::all(['nom', 'imei']);
    //     $truckData = $existingTrucks->pluck('imei', 'nom');
    //     $truckNames = $truckData->keys();

    //     // Compter le nombre total de calendriers à traiter pour la barre de progression
    //     $totalCalendars = ImportExcel::whereIn('camion', $truckNames)
    //         ->where('import_calendar_id', $lastmonth)
    //         ->count();

    //     // Ajouter une barre de progression
    //     $console->withProgressBar(range(1, $totalCalendars), function () use ($truckData, $apiService, $console, $truckNames, $lastmonth, $totalCalendars) {
    //         ImportExcel::whereIn('camion', $truckNames)
    //             ->where('import_calendar_id', $lastmonth)
    //             ->chunk(10, function ($calendars) use ($truckData, $apiService, $console, $totalCalendars) {
    //                 $calendars->each(function ($calendar) use ($truckData, $apiService, $console, $totalCalendars) {
    //                     // Assigner IMEI
    //                     $calendar->imei = $truckData->get(trim($calendar->camion));

    //                     // Convertir date_debut en \DateTime
    //                     $calendar_start_date = new \DateTime($calendar->date_debut);
                        
    //                     // Convertir date_fin en \DateTime ou laisser null si la date est vide
    //                     $calendar_end_date = $calendar->date_fin ? new \DateTime($calendar->date_fin) : null;

    //                     // Si date_fin est nulle, calculer en fonction de la durée de route
    //                     if ($calendar_end_date === null) {
    //                         $dureeEnHeures = floatval($calendar->delais_route);
    //                         if ($dureeEnHeures <= 1) {
    //                             // Fin de journée
    //                             $calendar_end_date = (clone $calendar_start_date)->setTime(23, 59, 59); // Fin de journée
    //                         } else {
    //                             // Ajouter des jours en fonction de la durée
    //                             $dureeEnJours = ceil($dureeEnHeures / 24);
    //                             $calendar_end_date = (clone $calendar_start_date)->modify("+$dureeEnJours days");
    //                         }
    //                     }

    //                     // Appeler l'API avec les objets \DateTime
    //                     $api = $apiService->getRfidAndDistanceWithImeiAndPeriod($calendar->imei, $calendar_start_date, $calendar_end_date);

    //                     // Stocker les résultats dans les champs appropriés
    //                     $calendar->rfid_chauffeur = $api['rfid'];
    //                     $calendar->distance = $api['distance'];
                        
    //                     // Incrementer la barre de progression après chaque traitement
    //                     $totalCalendars->advance();
    //                 });

    //                 // Mise à jour en batch dans la base de données
    //                 DB::transaction(function () use ($calendars) {
    //                     foreach ($calendars as $item) {
    //                         ImportExcel::where('id', $item->id)->update([
    //                             'distance' => $item->distance,
    //                             'imei' => $item->imei,
    //                             'rfid_chauffeur' => $item->rfid_chauffeur,
    //                         ]);
    //                     }
    //                 });
    //             });
    //         });

    //     // Fin de la barre de progression
    //     $console->finish();
    //     $console->newLine(); // Pour ajouter une nouvelle ligne après la barre de progression
    // }

    public function checkDistanceAndRfid($console)
    {
        try {
            $apiService = new GeolocalisationService();
            $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
            $existingTrucks = Vehicule::all(['nom', 'imei']);
            $truckData = $existingTrucks->pluck('imei', 'nom');
            $truckNames = $truckData->keys();

            ImportExcel::whereIn('camion', $truckNames)
            ->where('import_calendar_id', $lastmonth)
            ->chunk(10, function ($calendars) use ($truckData, $apiService) {
                $calendars->each(function ($calendar) use ($truckData, $apiService) {
                    // Assigner IMEI
                    $calendar->imei = $truckData->get(trim($calendar->camion));

                    // Convertir date_debut en \DateTime
                    $calendar_start_date = new \DateTime($calendar->date_debut);
                    // dd($calendar_start_date); // Pour visualiser la date

                    // Convertir date_fin en \DateTime ou laisser null si la date est vide
                    $calendar_end_date = $calendar->date_fin ? new \DateTime($calendar->date_fin) : null;

                    // Si date_fin est nulle, calculer en fonction de la durée de route
                    if ($calendar_end_date === null) {
                        $dureeEnHeures = floatval($calendar->delais_route);
                        if ($dureeEnHeures <= 1) {
                            // Fin de journée
                            $calendar_end_date = (clone $calendar_start_date)->setTime(23, 59, 59); // Fin de journée
                        } else {
                            // Ajouter des jours en fonction de la durée
                            $dureeEnJours = ceil($dureeEnHeures / 24);
                            $calendar_end_date = (clone $calendar_start_date)->modify("+$dureeEnJours days");
                        }
                    }

                    // Appeler l'API avec les objets \DateTime
                    $api = $apiService->getRfidAndDistanceWithImeiAndPeriod($calendar->imei, $calendar_start_date, $calendar_end_date);

                    // Stocker les résultats dans les champs appropriés
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
        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error('Erreur lors de la vérification de la distance et du RFID : ' . $e->getMessage());
            $console->error('Une erreur est survenue : ' . $e->getMessage());
        }
    }



}