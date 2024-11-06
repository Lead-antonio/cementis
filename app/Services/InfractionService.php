<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Infraction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InfractionService
{
    const EVENT_TYPES = [
        'Accélération brusque', 
        'Freinage brusque', 
    ];

    /**
     * Antonio
     * Vérifie les évenements par rapport au EVENT_TYPES dans un intervalle de dates donné.
     */
    public function checkInfraction($start_date, $end_date)
    {
        try {
            $penaliteService = new PenaliteService();
            // Cloner $start_date pour ne pas modifier la date de départ
            $end_date = clone $start_date;

            // Définir la date de fin au dernier jour du mois
            $end_date->modify('last day of this month')->setTime(23, 59, 59);
            // $startDate = Carbon::now()->subMonths(2)->endOfMonth();
            // $endDate = Carbon::now()->startOfMonth();

            $records = DB::table('event')
            ->select('imei', 'chauffeur', 'vehicule', 'type', 'odometer','vitesse', 'latitude', 'longitude', DB::raw("LEFT(date,10) as simple_date"), DB::raw("RIGHT(date,8) as heure"), 'date as date_heure')
            ->whereBetween('date', [$start_date, $end_date])
            ->orderBy('simple_date', 'ASC')
            ->orderBy('heure', 'ASC')->get();

            $results = [];
            $prevRecord = null;
            $firstValidRecord = null;
            $lastValidRecord = null;
            $maxSpeed = 0;

            foreach ($records as $record) {
                if(in_array(trim($record->type), self::EVENT_TYPES)){
                    $points = $penaliteService->getPointPenaliteByEventType($record->type);

                    $results[] = [
                        'imei' => $record->imei,
                        'chauffeur' => $record->chauffeur,
                        'vehicule' => $record->vehicule,
                        'type' => $record->type,
                        'distance' => 0,
                        'vitesse' => $record->vitesse,
                        'odometer' => $record->odometer,
                        'duree_infraction' => 1, 
                        'duree_initial' => 1, 
                        'date_debut' => $record->simple_date,
                        'date_fin' => $record->simple_date,
                        'heure_debut' => $record->heure,
                        'heure_fin' => $record->heure,
                        'date_heure_debut' => $record->date_heure,
                        'date_heure_fin' => $record->date_heure,
                        'gps_debut' => $record->latitude . ',' . $record->longitude,
                        'gps_fin' => $record->latitude . ',' . $record->longitude,
                        'point' => $points,
                        'insuffisance' => 0
                    ];
                }
            }
            return $results;
        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error('Erreur lors de la vérification des infractions : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Antonio
     * Enregistre les infractions détectées dans la base de données.
     */
    public function saveInfraction($console, $start_date, $end_date){
        $infractions = $this->checkInfraction($start_date, $end_date);

        $console->withProgressBar($infractions, function($item)  {
        // foreach($infractions as $item){
            $existingInfraction = Infraction::where('imei', $item['imei'])
                    ->where('rfid', $item['chauffeur'])
                    ->where('event', $item['type'])
                    ->where('date_debut', $item['date_debut'])
                    ->where('date_fin', $item['date_fin'])
                    ->where('heure_debut', $item['heure_debut'])
                    ->where('heure_fin', $item['heure_fin'])
                    ->first();
                    
            // Si l'infraction n'exsite pas, la créer
            if (!$existingInfraction) {
                if(isset($item['chauffeur']) && 
                   $item['chauffeur'] != "0000000000" && 
                   $item['chauffeur'] != "u00f0u00f0u00f0u00f0u00f0u00f0u00f0u00f0u00f0u00f0" &&
                   $item['chauffeur'] != "u00ffu00dfu00ffu00efu00ffu00efu00ffu00efu00ffu00ef"){
                    Infraction::create([
                        'imei' => $item['imei'],
                        'rfid' => $item['chauffeur'],
                        'vehicule' => $item['vehicule'],
                        'event' => trim($item['type']),
                        'distance' => $item['distance'],
                        'odometer' => $item['odometer'],
                        'duree_infraction' => $item['duree_infraction'],
                        'duree_initial' => $item['duree_initial'],
                        'vitesse' => $item['vitesse'],
                        'date_debut' => $item['date_debut'],
                        'date_fin' => $item['date_fin'],
                        'heure_debut' => $item['heure_debut'],
                        'heure_fin' => $item['heure_fin'],
                        'gps_debut' => $item['gps_debut'],
                        'gps_fin' => $item['gps_fin'],
                        'point' => $item['point'],
                        'insuffisance' => $item['insuffisance']
                    ]);
                }
            }
        });
    }
}