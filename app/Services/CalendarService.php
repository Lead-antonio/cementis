<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use App\Models\ImportExcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\Utils;

class CalendarService
{
    /**
     * Antonio
     * Vérification des infractions par rapport à la  période du calendrier.
     *
     */
    // public function checkCalendar($console){
    //     try{
    //         $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
    //         $startDate = Carbon::now()->subMonths(2)->endOfMonth();
    //         $endDate = Carbon::now()->startOfMonth();

    //         $calendars = ImportExcel::where('import_calendar_id', $lastmonth)->get();
            
    //         $infractions = Infraction::whereBetween('date_debut', [$startDate, $endDate])->whereBetween('date_fin', [$startDate, $endDate])->get();

    //         $console->withProgressBar($calendars, function($calendar) use ($infractions) {
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
    //         });

    //         $console->info('Tous les calendriers sont vérifies par rapport aux infractions.');
    //     } catch (Exception $e) {
    //         // Gestion des erreurs
    //         Log::error('Erreur lors de la vérification du calendrier: ' . $e->getMessage());
    //     }
    // }

    public function checkCalendar($console) {
        try {
            $lastmonth = DB::table('import_calendar')->latest('id')->value('id');
            $startDate = (new \DateTime())->modify('-2 months')->modify('last day of this month')->setTime(23, 59, 59);
            $endDate = (new \DateTime())->modify('first day of this month')->setTime(0, 0, 0);
    
            $calendars = ImportExcel::where('import_calendar_id', $lastmonth)->get();
            $infractions = Infraction::whereBetween('date_debut', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                     ->whereBetween('date_fin', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                     ->get();
    
            $console->withProgressBar($calendars, function($calendar) use ($infractions) {
                $dateDebut = new \DateTime($calendar->date_debut);
                $dateFin = $calendar->date_fin ? new \DateTime($calendar->date_fin) : null;
    
                if ($dateFin === null) {
                    // Convertir la durée en heures
                    $dureeEnHeures = floatval($calendar->delais_route);
                    // Calculer la date de fin en fonction de la durée
                    if ($dureeEnHeures <= 1) {
                        // Durée inférieure à une journée
                        $dateFin = (clone $dateDebut)->setTime(23, 59, 59); // Fin de la journée
                    } else {
                        $dureeEnJours = ceil($dureeEnHeures / 24);
                        // Durée d'une journée ou plus
                        $dateFin = (clone $dateDebut)->modify("+$dureeEnJours days");
                    }
                }
    
                $infractionsDuringCalendar = $infractions->filter(function ($infraction) use ($dateDebut, $dateFin, $calendar) {
                    $infractionDateDebut = new \DateTime($infraction->date_debut . ' ' . $infraction->heure_debut);
                    $infractionDateFin = new \DateTime($infraction->date_fin . ' ' . $infraction->heure_fin);
    
                    // Vérifier si l'événement se trouve dans la plage de dates du début et de fin de livraison
                    $isInfractionInCalendarPeriod = ($dateFin === null) 
                        ? $infractionDateDebut == $dateDebut 
                        : ($infractionDateDebut >= $dateDebut && $infractionDateDebut <= $dateFin) 
                        || ($infractionDateFin >= $dateDebut && $infractionDateFin <= $dateFin);
    
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
    
            $console->info('Tous les calendriers sont vérifiés par rapport aux infractions.');
        } catch (\Exception $e) {
            // Gestion des erreurs
            Log::error('Erreur lors de la vérification du calendrier: ' . $e->getMessage());
        }
    }

    /**
     * Antonio
     * Avoir tous les journées pendant tous le calendrier.
     *
     */
    public static function getAllJourneyDuringCalendar($console){
        try{
            $lastmonth = DB::table('import_calendar')->latest('id')->value('id');

            $calendarService = new CalendarService();
            $data_infraction = [];
            $journeys = [];

            $calendars = ImportExcel::where('import_calendar_id', $lastmonth)->get();
            $console->withProgressBar($calendars, function($calendar) use ($calendarService, &$journeys) {
                    $journey = $calendarService->splitCalendarByJourney($calendar);
                    if($journey){
                        $journeys = array_merge($journeys, $journey);
                    }
            });

            return $journeys;
        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error('Erreur lors de la vérification du temps du conduite continue qui cumul: ' . $e->getMessage());
        }
    }

    /**
     * Antonio
     * Split calendar by journey
     * @param collection calendar
     * return array journeys
     */ 
    public static function splitCalendarByJourney($calendar)
    {
        $mouvementService = new MovementService();
        $continueService = new ConduiteContinueService();
        $utils = new Utils();
        $journeys = [];
        $movements_during_calendar = $mouvementService->getAllMouvementDuringCalendar($calendar->id);
        
        // 1. Convertir les dates de début et de fin du calendrier en DateTime
        $calendarStartDate = new \DateTime($calendar->date_debut);
        $calendarEndDate = new \DateTime($calendar->date_fin);
        
        // 2. Prendre la première date du mouvement DRIVE comme point de départ de la première journée
        $firstDriveMovement = collect($movements_during_calendar)->firstWhere('type', 'DRIVE');
        $imei = $firstDriveMovement['imei'];
        $rfid = $firstDriveMovement['rfid'];

        if ($firstDriveMovement) {
            $currentJourneyStart = new \DateTime($firstDriveMovement['start_date'] . ' ' . $firstDriveMovement['start_hour']); // DateTime du premier DRIVE
        } else {
            // Si aucun mouvement DRIVE n'est trouvé, retourner un tableau vide ou lever une exception
            return $journeys;
        }

        // 3. Diviser le calendrier par périodes de 24 heures
        while ($currentJourneyStart < $calendarEndDate) {
            // Par défaut, la fin de la journée est +24 heures
            $currentJourneyEnd = (clone $currentJourneyStart)->modify('+24 hours');
            $longestStopDuration = 0;
            $nextJourneyStart = null;

            
            // Filtrer les mouvements dans la période actuelle (de $currentJourneyStart à $currentJourneyEnd)
            $currentDayMovements = collect($movements_during_calendar)->filter(function ($movement) use ($currentJourneyStart, $currentJourneyEnd) {
                $movementDate = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
                return $movementDate >= $currentJourneyStart && $movementDate < $currentJourneyEnd;
            });

            foreach ($currentDayMovements as $movement) {
                if ($movement['type'] === 'STOP') {
                    $stopStart = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
                    $stopEnd = new \DateTime($movement['end_date'] . ' ' . $movement['end_hour']);
                    $stopDuration = $utils->convertTimeToSeconds($movement['duration']); // Durée en secondes
                    
    
                    // Calculer si l'arrêt est pendant le jour ou la nuit
                    $isDayTimeStop = $utils->isNightPeriod($stopStart->format('H:i:s'), $stopEnd->format('H:i:s'));
                    
    
                    // Arrêt prolongé en journée (8 heures ou plus)
                    if ($isDayTimeStop && $stopDuration >= 10 * 3600) {
                        $currentJourneyEnd = $stopStart; // Terminer la journée à l'heure de début de l'arrêt
                        $nextJourneyStart = $stopEnd; // La prochaine journée commencera après l'arrêt
                        break;
                    }
                    // Arrêt prolongé pendant la nuit (10 heures ou plus)
                    elseif (!$isDayTimeStop && $stopDuration >= 8 * 3600) {
                        $currentJourneyEnd = $stopStart; // Terminer la journée à l'heure de début de l'arrêt
                        $nextJourneyStart = $stopEnd; // La prochaine journée commencera après l'arrêt
                        break;
                    }
                }
            }

            // $drive_duration = getDriveDurationCached($imei, $currentJourneyStart, $currentJourneyEnd);
            // Ajouter cette journée à la liste des journées
            $journeys[] = [
                'name_importation' => $calendar->name_importation,
                'calendar_id' => $calendar->id,
                'imei' => $imei,
                'rfid' => $rfid,
                'camion' => $calendar->camion,
                'start' => $currentJourneyStart->format('Y-m-d H:i:s'),
                'end' => $currentJourneyEnd->format('Y-m-d H:i:s'),
            ];
            // Passer à la journée suivante (ajouter 24 heures)
            $currentJourneyStart = $nextJourneyStart ? $nextJourneyStart : $currentJourneyEnd;
        }

        if (!empty($journeys)) {
            $lastJourneyIndex = count($journeys) - 1;
            $journeys[$lastJourneyIndex]['end'] = $calendarEndDate->format('Y-m-d H:i:s');
        }

        return $journeys;
    }
  
}