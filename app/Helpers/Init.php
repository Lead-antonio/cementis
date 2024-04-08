<?php

use App\Models\ImportExcel;
use App\Models\Rotation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Chauffeur;
use App\Models\PenaliteChauffeur;


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


if (!function_exists('driverTop')){
    function driverTop()
    {
        $driverTop = DB::table('penalite_chauffeur')
            ->select(DB::raw('MAX(chauffeur.id) AS drive_id'), 'chauffeur.nom AS nom_chauffeur', DB::raw('SUM(penalite.point_penalite) as total_penalite'))
            ->join('chauffeur', 'penalite_chauffeur.id_chauffeur', '=', 'chauffeur.id')
            ->join('penalite', 'penalite_chauffeur.id_penalite', '=', 'penalite.id')
            ->groupBy('penalite_chauffeur.id_chauffeur', 'chauffeur.nom')
            ->orderByRaw('SUM(penalite.point_penalite) ASC')
            ->limit(1)
            ->first();

        return $driverTop;
    }
}


if (!function_exists('driverWorst')){
    function driverWorst(){
        $driverWorst = DB::table('penalite_chauffeur')
        ->select(DB::raw('MAX(chauffeur.id) AS drive_id'), 'chauffeur.nom AS nom_chauffeur', DB::raw('SUM(penalite.point_penalite) as total_penalite'))
        ->join('chauffeur', 'penalite_chauffeur.id_chauffeur', '=', 'chauffeur.id')
        ->join('penalite', 'penalite_chauffeur.id_penalite', '=', 'penalite.id')
        ->groupBy('penalite_chauffeur.id_chauffeur', 'chauffeur.nom')
        ->orderByRaw('SUM(penalite.point_penalite) DESC')
        ->limit(1)
        ->first();

        return $driverWorst;
    }
}

if (!function_exists('topDriver')) {

    function topDriver()
    {
        $topChauffeur = PenaliteChauffeur::select('chauffeur.nom', 'penalite_chauffeur.id_chauffeur', DB::raw('SUM(penalite.point_penalite) as total_penalite'))
            ->join('penalite', 'penalite.id', '=', 'penalite_chauffeur.id_penalite')
            ->join('chauffeur', 'chauffeur.id', '=', 'penalite_chauffeur.id_chauffeur')
            ->groupBy('penalite_chauffeur.id_chauffeur', 'chauffeur.nom')
            ->orderBy ('total_penalite')
            ->get();
        
        return $topChauffeur;
    }

}

if (!function_exists('driverChart')) {
    function driverChart()
    {
        // Récupérez les données sur les pénalités
        $penalitesData = PenaliteChauffeur::select('id_chauffeur', DB::raw('COUNT(*) as penalite_count'))
        ->groupBy('id_chauffeur')
        ->get();

        // Récupérez les noms de chauffeur correspondant à chaque identifiant de chauffeur
        $chauffeursNames = Chauffeur::whereIn('id', $penalitesData->pluck('id_chauffeur'))->pluck('nom', 'id')->toArray();

        // Préparez les données pour le graphique
        $labels = [];
        $data = [];

        foreach ($penalitesData as $penalite) {
            $labels[] = $chauffeursNames[$penalite->id_chauffeur];
            $data[] = $penalite->penalite_count;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}

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
    function getEventMonthly($rfid_chauffeur){
        $moisActuel = Carbon::now()->month;
        $events = Event::where('chauffeur', $rfid_chauffeur)
            ->whereMonth('date', $moisActuel)
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

    function getPointPenaliteTotalMonthly($id_chauffeur){
        $moisActuel = Carbon::now()->month;
        $result = DB::table('penalite_chauffeur as pc')
            ->join('penalite as p', 'pc.id_penalite', '=', 'p.id')
            ->join('event as e', 'pc.id_event', '=', 'e.id')
            ->join('Import_excel as c', 'pc.id_calendar', '=', 'c.id')
            ->join('chauffeur as ch', 'pc.id_chauffeur', '=', 'ch.id')
            ->select('pc.id_chauffeur', 'ch.nom', DB::raw('SUM(p.point_penalite) AS total_point_penalite'))
            ->where('pc.id_chauffeur', $id_chauffeur)
            ->whereMonth('e.date', '=', $moisActuel)
            ->whereYear('e.date', '=', 2024)
            ->groupBy('pc.id_chauffeur', 'ch.nom')
            ->first();
        if($result){
            return $result;
        }else{
            return 0;
        }
    } 

}

//Récuperation des calendriers d'un chauffeur par mois
if (!function_exists('getCalendarOfDriverMonthly')) {

    function getCalendarOfDriverMonthly($chauffeur){
        $moisActuel = Carbon::now()->month;
        $livraisons = ImportExcel::where('rfid_chauffeur', $chauffeur)
            ->whereMonth('date_debut', $moisActuel)
            ->get();

        return $livraisons;
    }
}

if (!function_exists('getImeiOfCalendarTruck')) {

    function getImeiOfCalendarTruck($truck){
        $data = getUserVehicule();
        foreach($data as $arrayItem) {
            if($arrayItem["name"] === $truck) {
                return  $arrayItem["imei"];
            }
        }
        return null;
    }

}

if (!function_exists('getUserVehicule')) {

    function getUserVehicule(){
        // Formatage des dates au format YYYYMMDD

        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=0AFEAB2328492FB8118E37ECCAF5E79F&cmd=USER_GET_OBJECTS";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        return $data;
    }

}

if (!function_exists('getDistanceWithImeiAndPeriod')) {

    function getDistanceWithImeiAndPeriod($imei,$startDate, $endDate){
        // Formatage des dates au format YYYYMMDD

        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=0AFEAB2328492FB8118E37ECCAF5E79F&cmd=OBJECT_GET_ROUTE,".$imei.",".$startDate.",".$endDate.",20";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        return $data;
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
        // dd($data);
        
        if (!empty($data)) {
            foreach ($data as $item) {
                // Vérifiez si une entrée identique existe déjà dans la table Rotation
                $existingEvent = Event::where('imei', $item[2])
                ->where('date', $item[4])
                ->first();
                // Si aucune entrée identique n'existe, insérez les données dans la table Rotation
                if (!$existingEvent) {
                    if(isset($item[10]['rfid'])){
                        Event::create([
                            'imei' => $item[2],
                            'chauffeur' => $item[10]['rfid'],
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
}


    // function Update_importExcel($id_importcalendar){
    //     // $import_calendar->id
    //     //Recuperation de la date debut et fin du fichier inserer
    //     $date_debut = ImportExcel::where('import_calendar_id', $id_importcalendar)->first('date_debut');

    //     $max_id_import_excel = ImportExcel::where('import_calendar_id',  $id_importcalendar)->max('id');
    //     $date_finals = ImportExcel::where('id',$max_id_import_excel)->first('date_fin');

    //     if($date_finals->date_fin == null){
    //         $date_fin_fichier = ImportExcel::where('id',$max_id_import_excel)->first('date_debut');
    //         $date_finals = $date_fin_fichier->date_debut;
    //     }else{
    //         $date_finals = $date_finals->date_fin;
    //     }

    //     $import_calendar->update([
    //         'date_debut' => $date_debut->date_debut,
    //         'date_fin' => $date_finals
    //     ]);
    // }

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



