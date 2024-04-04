<?php

use App\Models\ImportExcel;
use App\Models\Rotation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Chauffeur;


if (!function_exists('fast_trans')) {

    function fast_trans($key, $replace, $default = null)
    {
        $value = __($key, $replace);
        if ($value == $key && $default != null) {
            $value = $default;
        }
        return $value;
    }

}

if (!function_exists('getRoutes')) {

    function getRoutes(){
        $url = 'www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=0AFEAB2328492FB8118E37ECCAF5E79F&cmd=USER_GET_ROUTES,865135060228283';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        dd($data);
    }

}


// Création du chauffeur à partir d'un évènement
if (!function_exists('createExistingDriverInEvent')) {
    function createExistingDriverInEvent(){
        $existingDrivers = Event::distinct()
                            ->where(function ($query) {
                                $query->whereNotNull('chauffeur')
                                    ->where('chauffeur','<>', '');
                            })
                            ->pluck('chauffeur');
        
        $existingDrivers->each(function ($name) {
            // Utilisez firstOrCreate pour éviter les doublons
            Chauffeur::firstOrCreate(['nom' => $name]);
        });
    }
}

// Récupérer les évènements d'un chauffeur par mois
if (!function_exists('getEventMonthly')) {
    function getEventMonthly($Chauffeur, $month){
        $events = Event::where('chauffeur', $Chauffeur)
            ->whereMonth('date', $month)
            ->get();
        
        return $events;
    }
}

// Récupération d'un chauffeur par son nom
if (!function_exists('getDriverByName')) {
    function getDriverByName($name){
        $existingDrivers = Chauffeur::where('nom','=', $name)
                            ->first();

        return $existingDrivers;
    }
}

//Récupération du somme totale d'un point de pénalité d'un chauffeur par mois
if (!function_exists('getPointPenaliteTotalMonthly')) {

    function getPointPenaliteTotalMonthly($id_chauffeur, $monthly){
        $result = DB::table('penalite_chauffeur as pc')
            ->join('penalite as p', 'pc.id_penalite', '=', 'p.id')
            ->join('event as e', 'pc.id_event', '=', 'e.id')
            ->join('import_excel as c', 'pc.id_calendar', '=', 'c.id')
            ->select('pc.id_chauffeur', DB::raw('SUM(p.point_penalite) AS total_point_penalite'))
            ->where('pc.id_chauffeur', $id_chauffeur)
            ->whereMonth('e.date', '=', $monthly)
            ->whereYear('e.date', '=', 2024)
            ->groupBy('pc.id_chauffeur')
            ->first();
        if($result){
            return $result->total_point_penalite;
        }else{
            return 0;
        }
    } 

}

//Récuperation des calendriers d'un chauffeur par mois
if (!function_exists('getJourneyOfDriverMonthly')) {

    function getJourneyOfDriverMonthly($chauffeur, $month){
        $livraisons = ImportExcel::where('rfid_chauffeur', $chauffeur)
            ->whereMonth('date_debut', $month)
            ->get();

        return $livraisons;
    }
}

// Récupération des derniers évènements dans l'API M-TEC Tracking et enregistrer dans la table Event
if (!function_exists('getEventFromApi')) {

    function getEventFromApi(){

        $url = 'www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=0AFEAB2328492FB8118E37ECCAF5E79F&cmd=OBJECT_GET_LAST_EVENTS';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
        
        if (!empty($data)) {
            foreach ($data as $item) {
                // Vérifiez si une entrée identique existe déjà dans la table Rotation
                $existingEvent = Event::where('imei', $item[2])
                ->where('date', $item[4])
                ->first();
                // Si aucune entrée identique n'existe, insérez les données dans la table Rotation
                if (!$existingEvent) {
                    Event::create([
                        'imei' => $item[2],
                        'chauffeur' => "",
                        'vehicule' => $item[3],
                        'type' => $item[0],
                        'date' => $item[4],
                        'description' => $item[1],
                    ]);
                }
            }
        }
    }    
}


if (!function_exists('calculerDureeTotale')) {
    function calculerDureeTotale($immatriculation)
    {
        // Récupérer tous les trajets pour le véhicule spécifié
        $trajets = Rotation::where('matricule', $immatriculation)->get();

        // Initialiser la durée totale à zéro
        $dureeTotale = 0;

        // Initialiser les dates de départ et d'arrivée
        $dateDepart = null;
        $dateArrivee = null;

        // Parcourir chaque trajet et calculer la durée totale
        foreach ($trajets as $trajet) {
            $dateHeur = Carbon::parse($trajet->date_heur);

            // Si le trajet est "Départ - Ibity", mettez à jour la date de départ
            if ($trajet->mouvement == 'Depart - Ibity' || $trajet->mouvement == 'Depart - Tamatave') {
                $dateDepart = $dateHeur;
            }

            // Si le trajet est "Arrivée - Ibity", mettez à jour la date d'arrivée et calculez la durée
            if ($trajet->mouvement == 'Arrivée - Ibity' || $trajet->mouvement == 'Arrivée - Tamatave') {
                $dateArrivee = $dateHeur;
                if ($dateDepart !== null) {
                    $dureeTotale += $dateDepart->diffInHours($dateArrivee);
                }
            }
        }

        return $dureeTotale;
    }

}



