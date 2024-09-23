<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use App\Models\Penalite;
use App\Models\Vehicule;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventService
{
    const ALLOWED_EVENT = [
        'Accélération brusque', 
        'Freinage brusque', 
        'Excès de vitesse en agglomération', 
        'Excès de vitesse hors agglomération', 
        'Survitesse excessive',
        'Survitesse sur la piste de Tritriva',
        'Survitesse sur la piste d\'Ibity',
        'TEMPS DE CONDUITE CONTINUE JOUR',
        'TEMPS DE CONDUITE CONTINUE NUIT',
    ];

    /**
     * Antonio
     * get Event last 7 jours et filtrer par les evenements existant puis enregistrer dans la base.
     *
     */
    public function saveEventLastSevenDays(){
        try{
            $apiService = new GeolocalisationService();
            $apiData= $apiService->getEventApi();
            
            $penalitesAllowed = Penalite::all()->toArray();
            $allowedTypes = array_column($penalitesAllowed, 'duree','event');
            $filteredData = [];

            // Parcourir les données de l'API
            foreach ($apiData as $event) {
                if (in_array($event[1], array_keys($allowedTypes)) && isset($event[10]['rfid'])) {
                    $filteredData[] = $event;
                }
            }

            if (!empty($filteredData)) {
                foreach ($filteredData as $item) {
                    // Vérifiez si une entrée identique existe déjà dans la table Rotation
                    $existingEvent = Event::where('imei', trim($item[2]))
                    ->where('date', $item['4'])
                    ->first();

                    // Si aucune entrée identique n'existe, insérez les données dans la table Rotation
                    if (!$existingEvent) {
                    
                        if(isset($item[10]['rfid']) && $item[10]['rfid'] != "0000000000"){
                            Event::create([
                                'imei' => $item[2],
                                'chauffeur' => $item[10]['rfid'],
                                'vehicule' => $item[3],
                                'type' => trim($item[1]),
                                'date' => $item[4],
                                'odometer' => $item[10]['odo'] ?? 0,
                                'vitesse' => $item[9],
                                'latitude' => $item[5],
                                'longitude' => $item[6],
                                'duree' => $allowedTypes[$item[1]],
                                'description' => trim($item[1]),
                            ]);
                        }
                    }
                    else {
                        Event::where('id', $existingEvent->id)
                        ->update([
                            'duree' => $allowedTypes[$existingEvent->type],
                        ]);
                    }
                }
            }
        }catch (Exception $e) {
            // Logger l'erreur pour la traçabilité
            Log::error('Erreur lors de l\'insertion des évenements : ' . $e->getMessage());
            
            return null;
        }
        
    }

    /**
     * Antonio
     * Enregister les events pour un imei et entre deux période.
     *
     * @param string imei
     * @param datetime start_date
     * @param datetime end_date
     * @return json|null
     */
    public function saveEventForPeriode($imei, $start_date, $end_date){
        try{
            $apiService = new GeolocalisationService();
            $apiData= $apiService->getEventForPeriodeApi($imei, $start_date, $end_date);
            
            $filteredData = [];
            // Parcourir les données de l'API
            if(!empty($apiData)){
                foreach ($apiData as $event) {
                    if (in_array($event[1], self::ALLOWED_EVENT) && isset($event[10]['rfid'])) {
                        $filteredData[] = $event;
                    }
                }
            }
            

            if (!empty($filteredData)) {
                foreach ($filteredData as $item) {
                    // Vérifiez si une entrée identique existe déjà dans la table Event
                    $existingEvent = Event::where('imei', trim($item[2]))
                    ->where('date', $item['4'])
                    ->where('type', trim($item['1']))
                    ->first();

                    // Si aucune entrée identique n'existe, insérez les données dans la table Event
                    if (!$existingEvent) {
                    
                        if(isset($item[10]['rfid']) && 
                           $item[10]['rfid'] != "0000000000" && 
                           trim($item[10]['rfid']) != trim("u00f0u00f0u00f0u00f0u00f0u00f0u00f0u00f0u00f0u00f0")){
                            Event::create([
                                'imei' => $item[2],
                                'chauffeur' => $item[10]['rfid'],
                                'vehicule' => $item[3],
                                'type' => trim($item[1]),
                                'date' => $item[4],
                                'odometer' => $item[10]['odo'] ?? 0,
                                'vitesse' => $item[9] ?? 0,
                                'latitude' => $item[5] ?? 0,
                                'longitude' => $item[6] ?? 0,
                                'duree' => 1,
                                'description' => trim($item[1]) ?? 0,
                            ]);
                        }
                    }
                }
            }
        }catch (Exception $e) {
            // Logger l'erreur pour la traçabilité
            Log::error('Erreur lors de l\'insertion des évenements : ' . $e->getMessage());
            
            return null;
        }
    }

    /**
     * Antonio
     * Enregistrer les évenements de dernier mois.
     *
     */
    // public function proccessEventForPeriod(){
    //     $trucks = Vehicule::get();
        
    //     $start_date = Carbon::now()->subMonth()->startOfMonth()->startOfDay();
    //     $end_date = Carbon::now()->subMonth()->endOfMonth()->endOfDay();
        
    //     foreach($trucks as $truck){
    //         self::saveEventForPeriode($truck->imei, $start_date, $end_date);
    //     }
    // }
    public function proccessEventForPeriod($console)
    {
        // Récupération des camions
        $trucks = Vehicule::get();
        
        // Définition des dates de la période
        $start_date = Carbon::now()->subMonth()->startOfMonth()->startOfDay();
        $end_date = Carbon::now()->subMonth()->endOfMonth()->endOfDay();
        
        // Affichage de la barre de progression
        $console->withProgressBar($trucks, function($truck) use ($start_date, $end_date) {
            // Traitement de chaque camion
            self::saveEventForPeriode($truck->imei, $start_date, $end_date);
        });

        // Message final lorsque le traitement est terminé
        $console->info('Tous les événements pour la période ont été traités.');
    }

}