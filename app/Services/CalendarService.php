<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use App\Models\ImportExcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarService
{
    /**
     * Antonio
     * Vérification des infractions par rapport à la  période du calendrier.
     *
     */
    // public function checkCalendar(){
    //     try{
    //         $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
    //         $startDate = Carbon::now()->subMonths(2)->endOfMonth();
    //         $endDate = Carbon::now()->startOfMonth();

    //         $calendars = ImportExcel::where('import_calendar_id', $lastmonth)->get();
            
    //         $infractions = Infraction::whereBetween('date_debut', [$startDate, $endDate])->whereBetween('date_fin', [$startDate, $endDate])->get();
        
    //         $calendarsInInfractions = [];

    //         foreach ($calendars as $calendar) {
    //             $dateDebut = Carbon::parse($calendar->date_debut);
    //             $dateFin = $calendar->date_fin ? Carbon::parse($calendar->date_fin) : null;

    //             if ($dateFin === null) {
    //                 // Convertir la durée en heures
    //                 $dureeEnHeures = floatval($calendar->delais_route);
    //                 // Calculer la date de fin en fonction de la durée
    //                 if ($dureeEnHeures <= 1) {
    //                     // Durée inférieure à une journée
    //                     $dateFin = $dateDebut->copy()->endOfDay();
    //                 } else {
    //                     $dureeEnJours = ceil($dureeEnHeures / 24);
    //                     // Durée d'une journée ou plus
    //                     $dateFin = $dateDebut->copy()->addDays($dureeEnJours);
    //                 }
    //             }
    //             $infractionsDuringCalendar = $infractions->filter(function ($infraction) use ($dateDebut, $dateFin, $calendar) {
    //                 $infractionDateDebut = Carbon::parse($infraction->date_debut ." ". $infraction->heure_debut);
    //                 $infractionDateFin = Carbon::parse($infraction->date_fin ." ". $infraction->heure_fin);

    //                 // Vérifier si l'événement se trouve dans la plage de dates du début et de fin de livraison
    //                 $isInfractionInCalendarPeriod = ($dateFin === null) ? $infractionDateDebut->eq($dateDebut) : 
    //                     ($infractionDateDebut->between($dateDebut, $dateFin) || $infractionDateFin->between($dateDebut, $dateFin));
                    
    //                 // Vérifier si l'IMEI et le véhicule correspondent à ceux de la ligne d'importation
    //                 $isMatchingVehicule = strpos($infraction->vehicule, $calendar->camion) !== false;
                
    //                 // Retourner vrai si l'événement est dans la période de livraison et correspond au véhicule
    //                 return $isInfractionInCalendarPeriod && $isMatchingVehicule;
    //             });

    //             if ($infractionsDuringCalendar->isNotEmpty()) {
    //                 $infractionIds = $infractionsDuringCalendar->pluck('id')->toArray();
    //                 Infraction::whereIn('id', $infractionIds)->update([
    //                     'calendar_id' => $calendar->id,
    //                 ]);
    //             }
    //         }
    //     } catch (Exception $e) {
    //         // Gestion des erreurs
    //         Log::error('Erreur lors de la vérification du calendrier: ' . $e->getMessage());
    //     }
    // }
    public function checkCalendar($console){
        try{
            $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
            $startDate = Carbon::now()->subMonths(2)->endOfMonth();
            $endDate = Carbon::now()->startOfMonth();

            $calendars = ImportExcel::where('import_calendar_id', $lastmonth)->get();
            
            $infractions = Infraction::whereBetween('date_debut', [$startDate, $endDate])->whereBetween('date_fin', [$startDate, $endDate])->get();

            $console->withProgressBar($calendars, function($calendar) use ($infractions) {
                $dateDebut = Carbon::parse($calendar->date_debut);
                $dateFin = $calendar->date_fin ? Carbon::parse($calendar->date_fin) : null;

                if ($dateFin === null) {
                    // Convertir la durée en heures
                    $dureeEnHeures = floatval($calendar->delais_route);
                    // Calculer la date de fin en fonction de la durée
                    if ($dureeEnHeures <= 1) {
                        // Durée inférieure à une journée
                        $dateFin = $dateDebut->copy()->endOfDay();
                    } else {
                        $dureeEnJours = ceil($dureeEnHeures / 24);
                        // Durée d'une journée ou plus
                        $dateFin = $dateDebut->copy()->addDays($dureeEnJours);
                    }
                }
                $infractionsDuringCalendar = $infractions->filter(function ($infraction) use ($dateDebut, $dateFin, $calendar) {
                    $infractionDateDebut = Carbon::parse($infraction->date_debut ." ". $infraction->heure_debut);
                    $infractionDateFin = Carbon::parse($infraction->date_fin ." ". $infraction->heure_fin);

                    // Vérifier si l'événement se trouve dans la plage de dates du début et de fin de livraison
                    $isInfractionInCalendarPeriod = ($dateFin === null) ? $infractionDateDebut->eq($dateDebut) : 
                        ($infractionDateDebut->between($dateDebut, $dateFin) || $infractionDateFin->between($dateDebut, $dateFin));
                    
                    // Vérifier si l'IMEI et le véhicule correspondent à ceux de la ligne d'importation
                    $isMatchingVehicule = strpos($infraction->vehicule, $calendar->camion) !== false;
                
                    // Retourner vrai si l'événement est dans la période de livraison et correspond au véhicule
                    return $isInfractionInCalendarPeriod && $isMatchingVehicule;
                });

                if ($infractionsDuringCalendar->isNotEmpty()) {
                    $infractionIds = $infractionsDuringCalendar->pluck('id')->toArray();
                    Infraction::whereIn('id', $infractionIds)->update([
                        'calendar_id' => $calendar->id,
                    ]);
                }
            });

            $console->info('Tous les calendriers sont vérifies par rapport aux infractions.');
        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error('Erreur lors de la vérification du calendrier: ' . $e->getMessage());
        }
    }
}