<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use App\Models\ImportExcel;
use App\Models\Vehicule;
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

    // check Infraction Survitesse par rapoort au calendrier
    public function checkCalendar($console, $planning) {
        try {
            $startDate = new \DateTime($planning->date_debut);
            // $endDate = new \DateTime($planning->date_fin);
            $endDate = clone $startDate;
            // Définir la date de fin au dernier jour du mois
            $endDate->modify('last day of this month')->setTime(23, 59, 59);

            // Liste des infractions concernées
            $ExcluEvents = ['Temps de repos hebdomadaire', 'Temps de repos minimum après une journée de travail'];
    
            $calendars = ImportExcel::where('import_calendar_id', $planning->id)->get();
            // $infractions = Infraction::whereNotIn('event', $ExcluEvents)
            //                          ->whereBetween('date_debut', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            //                          ->whereBetween('date_fin', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            //                          ->get();
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

    // Check Infraction temps de repos
    // public function checkTempsReposInfractions($console, $planning)
    // {
    //     try {
    //         // Récupérer les calendriers disponibles liés au planning
    //         $calendars = ImportExcel::where('import_calendar_id', $planning->id)->get();

    //         // Vérifier qu'on a bien des calendriers avant de continuer
    //         if ($calendars->isEmpty()) {
    //             $console->info("Aucun calendrier trouvé pour ce planning.");
    //             return;
    //         }

    //         $startDate = new \DateTime($planning->date_debut);
    //         $endDate = clone $startDate;
    //         // Définir la date de fin au dernier jour du mois
    //         $endDate->modify('last day of this month')->setTime(23, 59, 59);

    //         // Liste des infractions concernées
    //         $restEvents = ['Temps de repos hebdomadaire', 'Temps de repos minimum après une journée de travail'];

    //         // Récupérer toutes les infractions de type "Temps de repos"
    //         $infractions = Infraction::whereIn('event', $restEvents)
    //                                 ->whereBetween('date_debut', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
    //                                 ->whereBetween('date_fin', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
    //                                 ->whereNull('calendar_id') // Exclure celles qui ont déjà un calendar_id
    //                                 ->get();

    //         if ($infractions->isNotEmpty()) {
    //             // Sélectionner un calendar_id aléatoire parmi ceux disponibles
    //             $randomCalendar = $calendars->random();
    //             $randomCalendarId = $randomCalendar->id;

    //             // Mise à jour des infractions avec ce calendar_id
    //             Infraction::whereIn('id', $infractions->pluck('id')->toArray())
    //                 ->update(['calendar_id' => $randomCalendarId]);

    //             $console->info(count($infractions) . " infractions de repos mises à jour avec l'ID de calendrier : $randomCalendarId");
    //         } else {
    //             $console->info("Aucune infraction de repos trouvée pour mise à jour.");
    //         }
    //     } catch (\Exception $e) {
    //         // Gestion des erreurs
    //         Log::error('Erreur lors de la vérification des infractions de repos : ' . $e->getMessage());
    //     }
    // }
    public function checkTempsReposInfractions($console, $planning)
    {
        try {
            // Récupérer les calendriers disponibles liés au planning
            $calendars = ImportExcel::where('import_calendar_id', $planning->id)->get();

            // Vérifier qu'on a bien des calendriers avant de continuer
            if ($calendars->isEmpty()) {
                $console->info("Aucun calendrier trouvé pour ce planning.");
                return;
            }

            $startDate = new \DateTime($planning->date_debut);
            $endDate = clone $startDate;
            // Définir la date de fin au dernier jour du mois
            $endDate->modify('last day of this month')->setTime(23, 59, 59);

            // Liste des infractions concernées
            $restEvents = ['Temps de repos hebdomadaire', 'Temps de repos minimum après une journée de travail'];

            // Récupérer toutes les infractions de type "Temps de repos"
            $infractions = Infraction::whereIn('event', $restEvents)
                                    ->whereBetween('date_debut', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                    ->whereBetween('date_fin', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                    // ->whereNull('calendar_id') // Exclure celles qui ont déjà un calendar_id
                                    ->get();
            
            if ($infractions->isNotEmpty()) {
                // Parcourir chaque infraction
                foreach ($infractions as $infraction) {
                    // Récupérer l'import_excel correspondant à l'imei de l'infraction
                    $importExcel = ImportExcel::where('imei', $infraction->imei)->first();

                    if ($importExcel) {
                        // Mettre à jour l'infraction avec l'import_excel_id correspondant
                        $infraction->update(['calendar_id' => $importExcel->id]);

                        $console->info("Infraction ID {$infraction->id} mise à jour avec import_excel_id : {$importExcel->id}");
                    } else {
                        $console->info("Aucun import_excel trouvé pour l'imei : {$infraction->imei}");
                    }
                }
            } else {
                $console->info("Aucune infraction de repos trouvée pour mise à jour.");
            }
        } catch (\Exception $e) {
            // Gestion des erreurs
            Log::error('Erreur lors de la vérification des infractions de repos : ' . $e->getMessage());
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


    /**
     * Antonio
     * Split work by journey
     * @param string imei
     * return array journeys
     */ 
    // public static function splitWorkJouney($imei, $start_date, $end_date) 
    // {
    //     $mouvementService = new MovementService();
    //     $truckService = new TruckService();
    //     $utils = new Utils();

    //     $journeys = [];

    //     $immatricule = $truckService->getTruckPlateNumberByImei($imei);
    //     $movements_monthly = $mouvementService->getAllMovementByJourney($imei, $start_date, $end_date);
    //     $calendarStartDate = $start_date;
    //     $calendarEndDate = $end_date;

    //     $firstDriveMovement = collect($movements_monthly)->firstWhere('type', 'DRIVE');
    //     if (!$firstDriveMovement) return $journeys;

    //     $imei = $firstDriveMovement['imei'];
    //     $rfid = $firstDriveMovement['rfid'];
    //     $currentJourneyStart = new \DateTime($firstDriveMovement['start_date'] . ' ' . $firstDriveMovement['start_hour']);

    //     while ($currentJourneyStart < $calendarEndDate) {
    //         $currentJourneyEnd = (clone $currentJourneyStart)->modify('+24 hours');
    //         $longestStopDuration = 0;
    //         $nextJourneyStart = null;

    //         $currentDayMovements = collect($movements_monthly)->filter(function ($movement) use ($currentJourneyStart, $currentJourneyEnd) {
    //             $movementDate = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
    //             return $movementDate >= $currentJourneyStart && $movementDate < $currentJourneyEnd;
    //         });

    //         foreach ($currentDayMovements as $movement) {
    //             if ($movement['type'] === 'STOP') {
    //                 $stopStart = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
    //                 $stopEnd = new \DateTime($movement['end_date'] . ' ' . $movement['end_hour']);
    //                 $stopDuration = $utils->convertTimeToSeconds($movement['duration']);

    //                 // STOP doit être totalement dans la journée ET finir avant la fin
    //                 if (
    //                     $stopStart >= $currentJourneyStart &&
    //                     $stopEnd <= $currentJourneyEnd &&
    //                     $stopDuration >= 8 * 3600
    //                 ) {
    //                     // $currentJourneyEnd = $stopStart;
    //                     $nextJourneyStart = $stopEnd;
    //                     $longestStopDuration = $stopDuration;
    //                     break;
    //                 }

    //                 // Mémoriser la durée max des STOP même non valides
    //                 if ($stopStart >= $currentJourneyStart && $stopEnd <= $currentJourneyEnd) {
    //                     if ($stopDuration > $longestStopDuration) {
    //                         $longestStopDuration = $stopDuration;
    //                     }
    //                 }
    //             }
    //         }

    //         $journeys[] = [
    //             'imei' => $imei,
    //             'rfid' => $rfid,
    //             'camion' => $immatricule,
    //             'max_stop_duration' => $utils->convertDurationSecondsToTimeFormat($longestStopDuration),
    //             'start' => $currentJourneyStart->format('Y-m-d H:i:s'),
    //             'end' => $currentJourneyEnd->format('Y-m-d H:i:s'),
    //         ];

    //         $currentJourneyStart = $nextJourneyStart ?: $currentJourneyEnd;
    //     }

    //     // Ajuster la dernière journée si elle dépasse la fin
    //     if (!empty($journeys)) {
    //         $lastIndex = count($journeys) - 1;
    //         if ($journeys[$lastIndex]['end'] > $calendarEndDate->format('Y-m-d H:i:s')) {
    //             $journeys[$lastIndex]['end'] = $calendarEndDate->format('Y-m-d H:i:s');
    //         }
    //     }

    //     return $journeys;
    // }
    public static function splitWorkJouney($imei, $start_date, $end_date) 
    {
        $mouvementService = new MovementService();
        $truckService = new TruckService();
        $utils = new Utils();

        $journeys = [];

        $immatricule = $truckService->getTruckPlateNumberByImei($imei);
        $movements_monthly = $mouvementService->getAllMovementByJourney($imei, $start_date, $end_date);
        $calendarStartDate = $start_date;
        $calendarEndDate = $end_date;

        $firstDriveMovement = collect($movements_monthly)->firstWhere('type', 'DRIVE');
        if (!$firstDriveMovement) return $journeys;

        $imei = $firstDriveMovement['imei'];
        $rfid = $firstDriveMovement['rfid'];
        $currentJourneyStart = new \DateTime($firstDriveMovement['start_date'] . ' ' . $firstDriveMovement['start_hour']);
    
        while ($currentJourneyStart < $calendarEndDate) {
            $defaultJourneyEnd = (clone $currentJourneyStart)->modify('+24 hours');
            $currentJourneyEnd = clone $defaultJourneyEnd;
            $longestStopDuration = 0;
            $nextJourneyStart = null;
            $start_date_max = null;
            $end_date_max = null;

            $currentDayMovements = collect($movements_monthly)->filter(function ($movement) use ($currentJourneyStart, $currentJourneyEnd) {
                $movementDate = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
                // $end = new \DateTime($movement['end_date'] . ' ' . $movement['end_hour']);
                return $movementDate >= $currentJourneyStart && $movementDate < $currentJourneyEnd;
            });

            foreach ($currentDayMovements as $movement) {
                if ($movement['type'] === 'STOP') {
                    $stopStart = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
                    $stopEnd = new \DateTime($movement['end_date'] . ' ' . $movement['end_hour']);
                    $stopDuration = $utils->convertTimeToSeconds($movement['duration']);

                    $hoursSinceStart = ($stopStart->getTimestamp() - $currentJourneyStart->getTimestamp()) / 3600;

                    // Cas où le STOP est valide : >= 8h et commence dans les 16 premières heures
                    if (
                        $stopStart <= $currentJourneyEnd &&
                        $stopEnd >= $currentJourneyStart &&
                        $stopDuration >= 8 * 3600 &&
                        $hoursSinceStart <= 16
                    ) {
                        $currentJourneyEnd = $stopEnd;
                        $nextJourneyStart = $stopEnd;
                        $start_date_max = $stopStart;
                        $end_date_max = $stopEnd;
                        $longestStopDuration = $stopDuration;
                        break; // premier STOP valide trouvé
                    }

                    // Suivi du plus long STOP même s'il n'est pas valide
                    if ($stopStart >= $currentJourneyStart && $stopEnd <= $currentJourneyEnd) {
                        if ($stopDuration > $longestStopDuration) {
                            $start_date_max = $stopStart;
                            $end_date_max = $stopEnd;
                            $longestStopDuration = $stopDuration;
                        }
                    }
                }
            }

            $journeys[] = [
                'imei' => $imei,
                'rfid' => $rfid,
                'camion' => $immatricule,
                'moov_start_date' => $start_date_max ? $start_date_max->format('Y-m-d H:i:s') : null,
                'moov_end_date' => $end_date_max ? $end_date_max->format('Y-m-d H:i:s') : null,
                'max_stop_duration' => $utils->convertDurationSecondsToTimeFormat($longestStopDuration),
                'start' => $currentJourneyStart->format('Y-m-d H:i:s'),
                'end' => $currentJourneyEnd->format('Y-m-d H:i:s'),
            ];

            $currentJourneyStart = $nextJourneyStart ?? $defaultJourneyEnd;
        }

        // Ajustement de la dernière journée
        if (!empty($journeys)) {
            $lastIndex = count($journeys) - 1;
            if ($journeys[$lastIndex]['end'] > $calendarEndDate->format('Y-m-d H:i:s')) {
                $journeys[$lastIndex]['end'] = $calendarEndDate->format('Y-m-d H:i:s');
            }
        }

        return $journeys;
    }




        /**
     * Antonio
     * Split wrok weekly
     * @param string imei
     * @param DateTime start_date
     * @param DateTime end_date
     * return array work by journey
     */ 
    public static function getAllWorkJouneys($imei, $start_date, $end_date , $console = null)
    {
        try {
            $all_days = [];
            
            $days = self::splitWorkJouney($imei, $start_date, $end_date);

            $all_days = array_merge($all_days, $days);
            // dd($all_days);
            return $all_days;

        } catch (\Exception $e) {
            // En cas d'exception, affichage dans la console si $console est défini
            if ($console) {
                $console->error("Erreur dans getAllWorkWeekly: " . $e->getMessage());
            }
            
            // En cas d'exception
            \Log::error("Erreur dans getAllWorkWeekly: " . $e->getMessage());

            // Retourner un message d'erreur
            return $e->getMessage();
        }
    }


    public static function filterWeeks(array $weeks): array
    {
        return array_filter($weeks, function ($week) {
            // Calcul de l'intervalle en secondes entre 'start' et 'end' de chaque semaine
            $start = new \DateTime($week['start']);
            $end = new \DateTime($week['end']);
            $interval = $end->getTimestamp() - $start->getTimestamp();

            // Conversion de la durée maximale d'arrêt en secondes
            $longestStopDuration = (new Utils())->convertTimeToSeconds($week['max_stop_duration']);

            // Filtrer selon la condition requise
            return $interval >= (168 * 3600) || $longestStopDuration >= (24 * 3600);
        });
    }


    /**
     * Antonio
     * Split wrok weekly
     * @param string imei
     * @param DateTime start_date
     * @param DateTime end_date
     * return array week
     */ 
    // public static function splitWorkWeekly($imei, $start_date, $end_date)
    // {
    //     $mouvementService = new MovementService();
    //     $continueService = new ConduiteContinueService();
    //     $truckService = new TruckService();
    //     $utils = new Utils();
    //     $weeks = [];
    //     $movements_monthly = $mouvementService->getAllMouvementByImei($imei, $start_date, $end_date);
    //     $immatricule = $truckService->getTruckPlateNumberByImei($imei);
    //     $calendarStartDate = $start_date;
    //     $calendarEndDate = $end_date;
        
    //     // 2. Prendre le premier mouvement DRIVE comme point de départ de la première semaine
    //     $firstDriveMovement = collect($movements_monthly)->firstWhere('type', 'DRIVE');
    //     $imei = $firstDriveMovement['imei'];
    //     $rfid = $firstDriveMovement['rfid'];

    //     if ($firstDriveMovement) {
    //         $currentWeekStart = new \DateTime($firstDriveMovement['start_date'] . ' ' . $firstDriveMovement['start_hour']); // DateTime du premier DRIVE
    //     } else {
    //         // Si aucun mouvement DRIVE n'est trouvé, retourner un tableau vide ou lever une exception
    //         return $weeks;
    //     }

    //     // 3. Diviser le calendrier par semaines (168 heures)
    //     while ($currentWeekStart < $calendarEndDate) {
    //         // Par défaut, la fin de la semaine est +168 heures
    //         $currentWeekEnd = (clone $currentWeekStart)->modify('+168 hours');
    //         $longestStopDuration = 0;
    //         $nextWeekStart = null;

    //         // Filtrer les mouvements dans la semaine actuelle (de $currentWeekStart à $currentWeekEnd)
    //         $currentWeekMovements = collect($movements_monthly)->filter(function ($movement) use ($currentWeekStart, $currentWeekEnd) {
    //             $movementDate = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
    //             return $movementDate >= $currentWeekStart && $movementDate < $currentWeekEnd;
    //         });

    //         foreach ($currentWeekMovements as $movement) {
    //                 if ($movement['type'] === 'STOP') {
    //                     $stopStart = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
    //                     $stopEnd = new \DateTime($movement['end_date'] . ' ' . $movement['end_hour']);
    //                     $stopDuration = $utils->convertTimeToSeconds($movement['duration']); // Durée en secondes

    //                     // Calculer la durée maximale d'arrêt pendant cette semaine
    //                 if ($stopDuration > $longestStopDuration) {
    //                     $longestStopDuration = $stopDuration;
    //                 }

    //                 // Calculer si l'arrêt est supérieur à 24 heures
    //                 if ($stopDuration >= 24 * 3600) {
    //                     $currentWeekEnd = $stopEnd; // Terminer la semaine à l'heure de début de l'arrêt
    //                     $nextWeekStart = $stopEnd; // La prochaine semaine commencera après l'arrêt
    //                     break;
    //                 }
    //             }
    //         }

    //         if ($longestStopDuration > 0) {
    //             // Ajouter cette semaine à la liste des semaines
    //             $weeks[] = [
    //                 'name_importation' => null,
    //                 'calendar_id' => null,
    //                 'imei' => $imei,
    //                 'rfid' => $rfid,
    //                 'camion' => $immatricule,
    //                 'start' => $currentWeekStart->format('Y-m-d H:i:s'),
    //                 'end' => $currentWeekEnd->format('Y-m-d H:i:s'),
    //                 'max_stop_duration' => $utils->convertDurationSecondsToTimeFormat($longestStopDuration),
    //             ];
    //         }

    //         // Passer à la semaine suivante (après l'arrêt ou 168 heures)
    //         $currentWeekStart = $nextWeekStart ? $nextWeekStart : $currentWeekEnd;
    //     }

    //     // Ajuster la dernière semaine pour terminer à la date de fin du calendrier, si nécessaire
    //     if (!empty($weeks)) {
    //         $lastWeekIndex = count($weeks) - 1;
    //         $weeks[$lastWeekIndex]['end'] = $calendarEndDate->format('Y-m-d H:i:s');
    //     }

    //     return $weeks;
    // }
    // public static function splitWorkWeekly($imei, $start_date, $end_date)
    // {
    //     $mouvementService = new MovementService();
    //     $continueService = new ConduiteContinueService();
    //     $truckService = new TruckService();
    //     $utils = new Utils();
    //     $weeks = [];
        
    //     try {
    //         $immatricule = $truckService->getTruckPlateNumberByImei($imei);
    //         $movements_monthly = $mouvementService->getAllMouvementByImei($imei, $start_date, $end_date);
    //         $calendarStartDate = $start_date;
    //         $calendarEndDate = $end_date;
            
    //         // 2. Prendre le premier mouvement DRIVE comme point de départ de la première semaine
    //         $firstDriveMovement = collect($movements_monthly)->firstWhere('type', 'DRIVE');
    //         $imei = $firstDriveMovement['imei'] ?? null; // Assurez-vous que ces valeurs sont définies
    //         $rfid = $firstDriveMovement['rfid'] ?? null;

    //         if ($firstDriveMovement) {
    //             $currentWeekStart = new \DateTime($firstDriveMovement['start_date'] . ' ' . $firstDriveMovement['start_hour']); // DateTime du premier DRIVE
    //         } else {
    //             // Si aucun mouvement DRIVE n'est trouvé, retourner un tableau vide ou lever une exception
    //             return $weeks;
    //         }

    //         // 3. Diviser le calendrier par semaines (168 heures)
    //         while ($currentWeekStart < $calendarEndDate) {
    //             // Par défaut, la fin de la semaine est +168 heures
    //             $currentWeekEnd = (clone $currentWeekStart)->modify('+168 hours');
    //             $longestStopDuration = 0;
    //             $start_date_max_duration = null;
    //             $end_date_max_duration = null;
    //             $nextWeekStart = null;

    //             // Filtrer les mouvements dans la semaine actuelle (de $currentWeekStart à $currentWeekEnd)
    //             $currentWeekMovements = collect($movements_monthly)->filter(function ($movement) use ($currentWeekStart, $currentWeekEnd) {
    //                 $movementDate = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
    //                 return $movementDate >= $currentWeekStart && $movementDate < $currentWeekEnd;
    //             });

    //             foreach ($currentWeekMovements as $movement) {
    //                 if ($movement['type'] === 'STOP') {
    //                     $stopStart = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
    //                     $stopEnd = new \DateTime($movement['end_date'] . ' ' . $movement['end_hour']);
    //                     $stopDuration = $utils->convertTimeToSeconds($movement['duration']); // Durée en secondes

    //                     if ($stopStart <= $currentWeekEnd && $stopEnd >= $currentWeekStart) {
    //                         // Calculer la durée maximale d'arrêt pendant cette semaine
    //                         $hoursSinceWeekStart = ($stopStart->getTimestamp() - $currentWeekStart->getTimestamp()) / 3600;
                             
    //                         if($hoursSinceWeekStart <= 144){
    //                             if ($stopDuration > $longestStopDuration) {
    //                                 $start_date_max_duration = $stopStart;
    //                                 $end_date_max_duration = $stopEnd;
    //                                 $longestStopDuration = $stopDuration;
    //                             }
    
                                
    //                             // Calculer si l'arrêt est supérieur à 24 heures
    //                             if ($stopDuration >= (24 * 3600 - 600) && $hoursSinceWeekStart <= 144) {
    //                                 $currentWeekEnd = $stopEnd; // Terminer la semaine à l'heure de début de l'arrêt
    //                                 $nextWeekStart = $stopEnd; // La prochaine semaine commencera après l'arrêt
                                    
    //                                 $start_date_max_duration = $stopStart;
    //                                 $end_date_max_duration = $stopEnd;
    //                                 $longestStopDuration = $stopDuration;
    //                                 // break;
    //                             }
    //                         }
    //                     }
    //                 }
    //             }

    //             if ($longestStopDuration > 0) {
    //                 // Ajouter cette semaine à la liste des semaines
    //                 $weeks[] = [
    //                     'name_importation' => null,
    //                     'calendar_id' => null,
    //                     'imei' => $imei,
    //                     'rfid' => $rfid,
    //                     'camion' => $immatricule,
    //                     'moov_start_date' => $start_date_max_duration->format('Y-m-d H:i:s'),
    //                     'moov_end_date' => $end_date_max_duration->format('Y-m-d H:i:s'),
    //                     'start' => $currentWeekStart->format('Y-m-d H:i:s'),
    //                     'end' => $currentWeekEnd->format('Y-m-d H:i:s'),
    //                     'max_stop_duration' => $utils->convertDurationSecondsToTimeFormat($longestStopDuration),
    //                 ];
    //             }

    //             // Passer à la semaine suivante (après l'arrêt ou 168 heures)
    //             $currentWeekStart = $nextWeekStart ? $nextWeekStart : $currentWeekEnd;
    //         }

    //         // Ajuster la dernière semaine pour terminer à la date de fin du calendrier, si nécessaire
    //         if (!empty($weeks)) {
    //             $lastWeekIndex = count($weeks) - 1;
    //             if($weeks[$lastWeekIndex]['end'] > $calendarEndDate->format('Y-m-d H:i:s')){
    //                 // $weeks[$lastWeekIndex]['end'] = $calendarEndDate->format('Y-m-d H:i:s');
    //             }
    //         }

    //     } catch (\Exception $e) {
    //         // Gestion des exceptions
    //         \Log::error("Erreur lors de la division des semaines pour l'IMEI $imei : " . $e->getMessage());
    //         // Vous pouvez choisir de relancer l'exception ou de retourner un tableau vide
    //         return [];
    //     }

    //     return self::filterWeeks($weeks);
    // }
    // public static function splitWorkWeekly($imei, $start_date, $end_date)
    // {
    //     $mouvementService = new MovementService();
    //     $truckService = new TruckService();
    //     $utils = new Utils();
    //     $weeks = [];

    //     try {
    //         // Si les dates sont des strings, les convertir en DateTime
    //         if (!$start_date instanceof \DateTime) $start_date = new \DateTime($start_date);
    //         if (!$end_date instanceof \DateTime) $end_date = new \DateTime($end_date);

    //         $immatricule = $truckService->getTruckPlateNumberByImei($imei);
    //         $movements_monthly = $mouvementService->getAllMouvementByImei($imei, $start_date, $end_date);

    //         // Premier DRIVE pour débuter la première semaine
    //         $firstDriveMovement = collect($movements_monthly)->firstWhere('type', 'DRIVE');
    //         if (!$firstDriveMovement) return $weeks;

    //         $imei = $firstDriveMovement['imei'] ?? null;
    //         $rfid = $firstDriveMovement['rfid'] ?? null;

    //         $currentWeekStart = new \DateTime($firstDriveMovement['start_date'] . ' ' . $firstDriveMovement['start_hour']);

    //         while ($currentWeekStart < $end_date) {
    //             $currentWeekEnd = (clone $currentWeekStart)->modify('+168 hours'); // 1 semaine max
    //             $maxStopDuration = 0;
    //             $stopStartMax = null;
    //             $stopEndMax = null;
    //             $nextWeekStart = null;

    //             // Filtrer mouvements dans la semaine actuelle
    //             $currentWeekMovements = collect($movements_monthly)->filter(function ($movement) use ($currentWeekStart, $currentWeekEnd) {
    //                 $movementDate = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
    //                 return $movementDate >= $currentWeekStart && $movementDate < $currentWeekEnd;
    //             });

    //             foreach ($currentWeekMovements as $movement) {
    //                 if ($movement['type'] === 'STOP') {
    //                     $stopStart = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
    //                     $stopEnd = new \DateTime($movement['end_date'] . ' ' . $movement['end_hour']);
    //                     $stopDuration = $utils->convertTimeToSeconds($movement['duration']); // en secondes

    //                     // Vérifier si le stop est dans les 144h depuis le début de semaine
    //                     $hoursSinceWeekStart = ($stopStart->getTimestamp() - $currentWeekStart->getTimestamp()) / 3600;

    //                     if ($hoursSinceWeekStart <= 144) {
    //                         // Gérer le stop le plus long pour cette semaine
    //                         if ($stopDuration > $maxStopDuration) {
    //                             $maxStopDuration = $stopDuration;
    //                             $stopStartMax = $stopStart;
    //                             $stopEndMax = $stopEnd;
    //                         }

    //                         // Stop valide ≥ 23h50min = 23*3600 + 50*60
    //                         if ($stopDuration >= (23 * 3600 + 50 * 60)) {
    //                             $currentWeekEnd = $stopEnd; // fin de semaine anticipée
    //                             $nextWeekStart = $stopEnd;  // début semaine suivante
    //                             break; // stop trouvé, on termine la semaine
    //                         }
    //                     }
    //                 }
    //             }

    //             // Ajouter la semaine
    //             $weeks[] = [
    //                 'name_importation' => null,
    //                 'calendar_id' => null,
    //                 'imei' => $imei,
    //                 'rfid' => $rfid,
    //                 'camion' => $immatricule,
    //                 'moov_start_date' => $stopStartMax ? $stopStartMax->format('Y-m-d H:i:s') : null,
    //                 'moov_end_date' => $stopEndMax ? $stopEndMax->format('Y-m-d H:i:s') : null,
    //                 'start' => $currentWeekStart->format('Y-m-d H:i:s'),
    //                 'end' => $currentWeekEnd->format('Y-m-d H:i:s'),
    //                 'max_stop_duration' => $maxStopDuration ? $utils->convertDurationSecondsToTimeFormat($maxStopDuration) : null,
    //             ];

    //             // Passer à la semaine suivante
    //             $currentWeekStart = $nextWeekStart ?? $currentWeekEnd;
    //         }

    //     } catch (\Exception $e) {
    //         \Log::error("Erreur splitWorkWeekly IMEI $imei : " . $e->getMessage());
    //         return [];
    //     }

    //     return $weeks;
    // }

    public static function splitWorkWeekly($imei, $start_date, $end_date)
    {
        $mouvementService = new MovementService();
        $truckService = new TruckService();
        $utils = new Utils();
        $weeks = [];

        try {
            if (!$start_date instanceof \DateTime) $start_date = new \DateTime($start_date);
            if (!$end_date instanceof \DateTime) $end_date = new \DateTime($end_date);

            $immatricule = $truckService->getTruckPlateNumberByImei($imei);
            $movements_monthly = $mouvementService->getAllMouvementByImei($imei, $start_date, $end_date);

            $firstDriveMovement = collect($movements_monthly)->firstWhere('type', 'DRIVE');
            if (!$firstDriveMovement) return $weeks;

            $imei = $firstDriveMovement['imei'] ?? null;
            $rfid = $firstDriveMovement['rfid'] ?? null;

            $currentWeekStart = new \DateTime($firstDriveMovement['start_date'] . ' ' . $firstDriveMovement['start_hour']);

            while ($currentWeekStart < $end_date) {
                $weekMaxEnd = (clone $currentWeekStart)->modify('+168 hours');
                $currentWeekEnd = $weekMaxEnd < $end_date ? $weekMaxEnd : clone $end_date;

                $maxStopDuration = 0;
                $stopStartMax = null;
                $stopEndMax = null;
                $nextWeekStart = null;

                $currentWeekMovements = collect($movements_monthly)->filter(function ($movement) use ($currentWeekStart, $currentWeekEnd) {
                    $movementDate = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
                    return $movementDate >= $currentWeekStart && $movementDate < $currentWeekEnd;
                });

                foreach ($currentWeekMovements as $movement) {
                    if ($movement['type'] === 'STOP') {
                        $stopStart = new \DateTime($movement['start_date'] . ' ' . $movement['start_hour']);
                        $stopEnd = new \DateTime($movement['end_date'] . ' ' . $movement['end_hour']);
                        $stopDuration = $utils->convertTimeToSeconds($movement['duration']);

                        $hoursSinceWeekStart = ($stopStart->getTimestamp() - $currentWeekStart->getTimestamp()) / 3600;

                        if ($hoursSinceWeekStart <= 144 && $stopEnd <= $end_date) {
                            if ($stopDuration > $maxStopDuration) {
                                $maxStopDuration = $stopDuration;
                                $stopStartMax = $stopStart;
                                $stopEndMax = $stopEnd;
                            }

                            if ($stopDuration >= (23 * 3600 + 50 * 60)) {
                                $currentWeekEnd = $stopEnd <= $end_date ? $stopEnd : clone $end_date;
                                $nextWeekStart = $stopEnd <= $end_date ? $stopEnd : null;
                                break;
                            }
                        }
                    }
                }

                // Vérifier si c'est la dernière semaine
                $isLastWeek = $currentWeekEnd >= $end_date;

                if ($isLastWeek) {
                    // calcul durée
                    $duration = $currentWeekStart->diff($currentWeekEnd);
                    $hours = ($duration->days * 24) + $duration->h + ($duration->i / 60);

                    if ($hours < 144) {
                        // trop court -> on ignore cette semaine
                        break;
                    }
                }

                $weeks[] = [
                    'name_importation' => null,
                    'calendar_id' => null,
                    'imei' => $imei,
                    'rfid' => $rfid,
                    'camion' => $immatricule,
                    'moov_start_date' => $stopStartMax ? $stopStartMax->format('Y-m-d H:i:s') : null,
                    'moov_end_date' => $stopEndMax ? $stopEndMax->format('Y-m-d H:i:s') : null,
                    'start' => $currentWeekStart->format('Y-m-d H:i:s'),
                    'end' => $currentWeekEnd->format('Y-m-d H:i:s'),
                    'max_stop_duration' => $maxStopDuration ? $utils->convertDurationSecondsToTimeFormat($maxStopDuration) : null,
                ];

                if (!$nextWeekStart) {
                    $nextWeekStart = (clone $currentWeekEnd);
                }
                $currentWeekStart = $nextWeekStart;

                if ($currentWeekStart >= $end_date) break;
            }

        } catch (\Exception $e) {
            \Log::error("Erreur splitWorkWeekly IMEI $imei : " . $e->getMessage());
            return [];
        }

        return $weeks;
    }





    /**
     * Antonio
     * Split wrok weekly
     * @param string imei
     * @param DateTime start_date
     * @param DateTime end_date
     * return array work weekly
     */ 
    public static function getAllWorkWeekly($start_date, $end_date)
    {
        try {
            $all_trucks = Vehicule::all();
            $all_week = [];
            // dd($start_date, $end_date);
            foreach ($all_trucks as $truck) {
                $weeks = self::splitWorkWeekly($truck->imei, $start_date, $end_date);
                // $weeks = self::splitWorkWeekly("865135060348347", $start_date, $end_date); //6636TCC
                // $weeks = self::splitWorkWeekly("865135060650460", $start_date, $end_date); //6596TCC
                $all_week = array_merge($all_week, $weeks);
                // dd($all_week);
            }
            return $all_week;

        } catch (\Exception $e) {
            // En cas d'exception
            \Log::error("Erreur dans getAllWorkWeekly: " . $e->getMessage());

            // throw new \Exception("Erreur lors de la récupération des semaines de travail : " . $e->getMessage());

            // Retourner un message d'erreur
            return response()->json(['error' => 'Une erreur est survenue lors du traitement des semaines de travail.'], 500);
        }
    }


    public static function CleanCalendar($planning){
        try{
            $idsToDelete = DB::table('import_excel')
                ->select('id')
                ->whereRaw('(camion, badge_chauffeur, date_debut, date_fin) NOT IN (
                    SELECT camion, badge_chauffeur, date_debut, MAX(date_fin)
                    FROM import_excel
                    GROUP BY camion, date_debut, badge_chauffeur
                )')
                ->where('import_calendar_id', $planning->id)
                ->pluck('id');
                
            // Si rien à supprimer, on sort
            if ($idsToDelete->isEmpty()) {
                Log::info("CleanCalendar: Aucun doublon à supprimer pour import_calendar_id = $planning->id.");
                return [
                    'status' => 'success',
                    'message' => 'Aucun doublon trouvé à supprimer.',
                    'deleted_count' => 0
                ];
            }

            // Étape 2 : Supprimer les doublons
            $deleted = DB::table('import_excel')
                ->whereIn('id', $idsToDelete)
                ->delete();

            Log::info("CleanCalendar: $deleted doublon(s) supprimé(s) pour import_calendar_id = $planning->id.");

            return [
                'status' => 'success',
                'message' => "$deleted doublon(s) supprimé(s).",
                'deleted_count' => $deleted
            ];
        }catch (\Exception $e) {
            // Gestion des erreurs
            Log::error("CleanCalendar: Erreur lors du nettoyage - " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => 'Erreur lors de la suppression des doublons.',
                'error' => $e->getMessage()
            ];
        }

    }


  
}