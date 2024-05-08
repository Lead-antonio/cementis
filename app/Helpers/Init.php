<?php

use App\Models\ImportExcel;
use App\Models\Rotation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Chauffeur;
use App\Models\Penalite;
use App\Models\PenaliteChauffeur;
use App\Models\GroupeEvent;
use App\Models\Transporteur;
use App\Models\Infraction;

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


if (!function_exists('totalScoringCard')) {

    function totalScoringCard()
    {
        $result = DB::table('penalite_chauffeur as pc')
            ->join('chauffeur as ch', 'pc.id_chauffeur', '=', 'ch.id')
            ->join('penalite as p', 'pc.id_penalite', '=', 'p.id')
            ->join('transporteur as t', 'ch.transporteur_id', '=', 't.id')
            ->select(
                'ch.nom as driver','ch.id as id_driver','t.nom as transporteur',
                DB::raw('SUM(p.point_penalite) as total_penalty_point'),
                DB::raw('SUM(pc.distance) as total_distance'),
                DB::raw('(SUM(p.point_penalite) * 100) / SUM(pc.distance) as score_card')
            )
            ->groupBy('ch.nom', 'ch.id','t.nom')
            ->orderBy('ch.nom')
            ->orderBy('ch.id')
            ->get();

        return $result;
    }
}



if (!function_exists('tabScoringCard')) {
    function tabScoringCard()
    {
        // $results = DB::table('penalite_chauffeur as pc')
        //     ->join('chauffeur as ch', 'pc.id_chauffeur', '=', 'ch.id')
        //     ->join('penalite as p', 'pc.id_penalite', '=', 'p.id')
        //     ->join('event as e', 'pc.id_event', '=', 'e.id')
        //     ->join('transporteur as t', 'ch.transporteur_id', '=', 't.id')
        //     ->select(
        //         'ch.nom as driver',
        //         't.nom as transporteur_nom',
        //         'e.duree as duree',
        //         'e.latitude as latitude',
        //         'e.longitude as longitude',
        //         // DB::raw("DATE_FORMAT(pc.date, '%Y-%m') as Month"),
        //         // DB::raw("DATE_FORMAT(pc.date, '%Y-%m-%d %H:%i:%s') as date_event"),
        //         'pc.date as date_event',
        //         'e.type as event',
        //         'p.point_penalite as penalty_point',
        //         'pc.distance as distance',
        //         DB::raw("(p.point_penalite * 100) / pc.distance as score_card")
        //     )
        //     ->groupBy('t.nom','ch.nom', 'e.duree', 'e.latitude', 'e.longitude', 'pc.date', 'e.type', 'p.point_penalite', 'pc.distance')
        //     // ->orderByDesc('pc.date')
            
        //     ->orderBy('ch.nom')
        //     ->orderBy('t.nom')
        //     ->orderBy('e.date')
        //     // ->orderBy('e.duree')
        //     // ->orderBy('e.latitude')
        //     // ->orderBy('e.longitude')
        //     // ->orderBy(DB::raw("DATE_FORMAT(pc.date, '%Y-%m-%d %H:%i:%s')"))
        //     ->get();

        //     // DB::raw("DATE_FORMAT(pc.date, '%Y-%m')")
        $results = DB::table('infraction as i')
        ->join('chauffeur as ch', 'i.rfid', '=', 'ch.rfid')
        ->join('import_excel as ie', 'i.calendar_id', '=', 'ie.id')
        ->join('transporteur as t', 'ch.transporteur_id', '=', 't.id')
        ->select(
            'ch.nom as driver',
            't.nom as transporteur_nom',
            'i.event as infraction',
            'i.date_debut',
            'i.heure_debut',
            'i.date_fin',
            'i.heure_fin',
            'i.duree_infraction',
            'i.duree_initial',
            'i.odometer',
            'i.gps_debut',
            'i.distance',
            'i.distance_calendar',
            'i.point'
        )
        ->orderBy('ch.nom')
        ->get();

        return $results;
    }

}


if (!function_exists('tabScoringCard_new')) {
    function tabScoringCard_new()
    {
        $results = DB::table('infraction as i')
        ->join('chauffeur as ch', 'i.rfid', '=', 'ch.rfid')
        ->join('import_excel as ie', 'i.calendar_id', '=', 'ie.id')
        ->join('transporteur as t', 'ch.transporteur_id', '=', 't.id')
        ->select(
            'ch.nom as driver',
            't.nom as transporteur_nom',
            'i.gps_debut as latitude',
            'i.gps_fin as longitude',
            'i.duree_infraction as duree',
            DB::raw("CONCAT(i.date_debut, ' ', i.heure_debut) as date_event"),
            'i.event as event',
            'i.point as penalty_point',
            'i.distance',
            'i.distance_calendar',
            DB::raw("(i.point * 100) / i.distance_calendar as score_card")
            )
        ->groupBy('t.nom','ch.nom', 'i.duree_infraction','i.heure_debut','i.heure_fin', 'i.gps_debut', 'i.date_debut', 'i.gps_fin', 'i.event', 'i.point', 'i.distance','i.distance_calendar')
        ->orderBy('ch.nom')
        ->orderBy('t.nom')
        ->get();

            // DB::raw("DATE_FORMAT(pc.date, '%Y-%m')")
        
        return $results;
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

if (!function_exists('scoringCard')) {

    function scoringCard()
    {
        // $data = null;
        $data = Chauffeur::select('chauffeur.id AS id_chauffeur', 'chauffeur.nom',
            DB::raw('COALESCE((SUM(penalite.point_penalite) * 100) / NULLIF(SUM(penalite_chauffeur.distance), 0), 0) AS scoring_card'))
            ->leftJoin('penalite_chauffeur', 'chauffeur.id', '=', 'penalite_chauffeur.id_chauffeur')
            ->leftJoin('penalite', 'penalite.id', '=', 'penalite_chauffeur.id_penalite')
            ->leftJoin('import_excel', 'penalite_chauffeur.id_calendar', '=', 'import_excel.id')
            ->groupBy('chauffeur.id', 'chauffeur.nom')
            ->orderBy('scoring_card', 'asc')
            ->get();
        
        return $data;
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

if(!function_exists('TotalScoringbyDriver')){
    
    function TotalScoringbyDriver()
    {
        $results = DB::table('penalite_chauffeur as pc')
        ->join('chauffeur as ch', 'pc.id_chauffeur', '=', 'ch.id')
        ->join('penalite as p', 'pc.id_penalite', '=', 'p.id')
        ->join('transporteur as t', 'ch.transporteur_id', '=', 't.id')
        ->select(
            'ch.nom as driver',
            't.nom as transporteur_nom',
            DB::raw('SUM(p.point_penalite) as total_penalty_point'),
            DB::raw('SUM(DISTINCT pc.distance) as total_distance'), // Totalité de la distance
            DB::raw('ROUND((SUM(p.point_penalite) * 100) / SUM(DISTINCT pc.distance), 2) as score_card')// Utilisation de SUM(DISTINCT) pour obtenir la somme des distances uniques
        )
        ->groupBy('ch.nom', 't.nom')
        ->orderBy('t.nom')
        ->orderBy('ch.nom')
        ->get();

        return $results;

    }

}


if(!function_exists('getAllGoodScoring')){
    function getAllGoodScoring(){

    // $results = DB::table('infraction as i')
    //     ->join('chauffeur as ch', 'i.rfid', '=', 'ch.rfid')
    //     ->join('import_excel as ie', 'i.calendar_id', '=', 'ie.id')
    //     ->join('transporteur as t', 'ch.transporteur_id', '=', 't.id')
    //     ->select(
    //         'ch.nom as driver',
    //         't.nom as transporteur_nom',
    //         'i.event as infraction',
    //         'i.date_debut',
    //         'i.heure_debut',
    //         'i.date_fin',
    //         'i.heure_fin',
    //         'i.duree_infraction',
    //         'i.duree_initial',
    //         'i.odometer',
    //         'i.distance',
    //         'i.point'
    //     )
    //     ->get();

    $results = DB::table('infraction as i')
    ->join('chauffeur as ch', 'i.rfid', '=', 'ch.rfid')
    ->join('import_excel as ie', 'i.calendar_id', '=', 'ie.id')
    ->join('transporteur as t', 'ch.transporteur_id', '=', 't.id')
    ->select(
        'ch.nom as driver',
        't.nom as transporteur_nom',
        DB::raw('ROUND((SUM(i.point) * 100) / SUM(DISTINCT i.distance_calendar), 2) as scoring_card')
    )
    ->groupBy('ch.nom', 't.nom')->orderBy('scoring_card','asc')
    ->take(10)
    ->get();

    return $results;

    }
}


if(!function_exists('getAllBadScoring')){
    function getAllBadScoring(){

        $results = DB::table('infraction as i')
        ->join('chauffeur as ch', 'i.rfid', '=', 'ch.rfid')
        ->join('import_excel as ie', 'i.calendar_id', '=', 'ie.id')
        ->join('transporteur as t', 'ch.transporteur_id', '=', 't.id')
        ->select(
            'ch.nom as driver',
            't.nom as transporteur_nom',
            DB::raw('ROUND((SUM(i.point) * 100) / SUM(DISTINCT i.distance_calendar), 2) as scoring_card')
        )
        ->groupBy('ch.nom', 't.nom')->orderBy('scoring_card','DESC')
        ->take(10)
        ->get();
        return $results;

    }
}



if(!function_exists('topAndWorstChauffeur')){
    
    function topAndWorstChauffeur()
    {

        $results = TotalScoringbyDriver();

        // $results = tabScoringCard(); // Appel de votre fonction pour obtenir les résultats de la requête

        $topAndWorstDrivers = [];


        // Groupement des résultats par transporteur
        $resultsByTransporteur = $results->groupBy('transporteur_nom');

        // Pour chaque transporteur
        foreach ($resultsByTransporteur as $transporteur => $resultats) {
            // Trier les résultats par score_card (du plus petit au plus grand)
            $sortedResults = $resultats->sortBy('score_card');

            // Obtenir les 3 meilleurs chauffeurs
            $topChauffeurs = $sortedResults->take(3);

            // Obtenir les 3 pires chauffeurs
            $worstChauffeurs = $sortedResults->reverse()->take(3);

            // Collecter les résultats dans un tableau
            $topAndWorstDrivers[] = [
                'transporteur' => $transporteur,
                'top_chauffeurs' => $topChauffeurs,
                'worst_chauffeurs' => $worstChauffeurs,
            ];
        }

        return $topAndWorstDrivers;
    }

   
  
}

if (!function_exists('driverChart')) {
    function driverChart()
    {
        $labels = [];
        $data = [];

        $chartScoring = Chauffeur::select('chauffeur.id AS id_chauffeur', 'chauffeur.nom',
            DB::raw('COALESCE((SUM(penalite.point_penalite) * 100) / NULLIF(SUM(penalite_chauffeur.distance), 0), 0) AS scoring_card'))
            ->leftJoin('penalite_chauffeur', 'chauffeur.id', '=', 'penalite_chauffeur.id_chauffeur')
            ->leftJoin('penalite', 'penalite.id', '=', 'penalite_chauffeur.id_penalite')
            ->leftJoin('import_excel', 'penalite_chauffeur.id_calendar', '=', 'import_excel.id')
            ->groupBy('chauffeur.id', 'chauffeur.nom')
            ->orderBy('scoring_card', 'asc')
            ->get();

        // dd($chartScoring);
        foreach ($chartScoring as $chart) {
            $labels[] = $chart->nom;
            $data[] = $chart->scoring_card;
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

if (!function_exists('getNameByRFID')) {
    function getNameByRFID($rfid)
    {
            $chauffeur = Chauffeur::where('rfid', $rfid)->first();

            if ($chauffeur) {
                return $chauffeur->nom;
            } else {
                return null;
            }
    }
}

if (!function_exists('getIdByRFID')) {
    function getIdByRFID($rfid)
    {
            $chauffeur = Chauffeur::where('rfid', $rfid)->first();

            if ($chauffeur) {
                return $chauffeur->id;
            } else {
                return null;
            }
    }
}

//Récupération du somme totale d'un point de pénalité d'un chauffeur par mois
if (!function_exists('getPointPenaliteTotalMonthly')) {

    function getPointPenaliteTotalMonthly($id_chauffeur){
        $moisActuel = Carbon::now()->month;
        $result = DB::table('penalite_chauffeur as pc')
            ->join('penalite as p', 'pc.id_penalite', '=', 'p.id')
            ->join('event as e', 'pc.id_event', '=', 'e.id')
            ->join('import_excel as c', 'pc.id_calendar', '=', 'c.id')
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

    function getCalendarOfDriverMonthly(){
        $moisActuel = Carbon::now()->month;
        $livraisons = ImportExcel::whereMonth('date_debut', $moisActuel)
            ->get();

        return $livraisons;
    }
}

if (!function_exists('getImeiOfCalendarTruck')) {

    function getImeiOfCalendarTruck($data, $truck){
        foreach($data as $arrayItem) {
            if($arrayItem["plate_number"] === $truck) {
                return  $arrayItem["imei"];
            }
        }
        return null;
    }

}

if (!function_exists('getUserVehicule')) {
    function getUserVehicule(){
        // Formatage des dates au format YYYYMMDD

        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=USER_GET_OBJECTS";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        return $data;

    }

}

if (!function_exists('insertPenaliteDrive')) {
    function insertPenaliteDrive($event, $calendar, $penalite){
            PenaliteChauffeur::updateOrCreate([
                'id_chauffeur' => getIdByRFID($event->chauffeur),
                'id_calendar' => $calendar->id,
                'id_event' => $event->id,
                'id_penalite' => $penalite->id,
                'duree' => $event->duree,
                'date' => $event->date,
            ]);
    }
}

if(!function_exists('ApiToFileJson')){
    function ApiToFileJson(){
        // Formatage des dates au format YYYYMMDD
        $totalRecords = PenaliteChauffeur::where('distance', '=', 0.00)->where('drive_duration', '=', 0.00)->get();
        
        foreach ($totalRecords as $item) {
            $start_date = Carbon::parse($item->related_calendar->date_debut);
            $end_date = $item->related_calendar->date_fin ? Carbon::parse($item->related_calendar->date_fin) : null;
            $imei = $item->related_event->imei;
            $rfid = $item->related_event->chauffeur;
            $event_date = Carbon::parse($item->related_event->date);

            if ($end_date === null) {
                $dureeEnHeures = floatval($item->related_calendar->delais_route);
                if ($dureeEnHeures <= 1) {
                    $end_date = $start_date->copy()->endOfDay();
                } else {
                    $dureeEnJours = ceil($dureeEnHeures / 24);
                    $end_date = $start_date->copy()->addDays($dureeEnJours);
                }
            }

            $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=OBJECT_GET_ROUTE,".$imei.",".$start_date->format('YmdHis').",".$end_date->format('YmdHis').",20";
            $response = Http::timeout(300)->get($url);
            $data = $response->json();
            $file_name = 'Api_'.$item->id.'.json';
            $file_path = public_path('Data/'. $file_name);
            if($response->successful()) {
                // Enregistrement des données dans un fichier JSON
                file_put_contents($file_path, json_encode($data));// Indique que l'opération s'est déroulée avec succès
            } 
        }

        return true;

        
    }

}


if (!function_exists('getDistanceWithImeiAndPeriod')) {

    function getDistanceWithImeiAndPeriod($rfid_chauffeur, $imei_vehicule, $start_date, $end_date){
        // Formatage des dates au format YYYYMMDD
        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=OBJECT_GET_ROUTE,".$imei_vehicule.",".$start_date->format('YmdHis').",".$end_date->format('YmdHis').",20";
        $response = Http::timeout(300)->get($url);
        $data = $response->json();

        $firstItem = reset($data['route']);
        $lastItem = end($data['route']);
        
        $firstOdo = null;
        $lastOdo = null;
        if ($firstItem[6]['rfid'] === $rfid_chauffeur && $lastItem[6]['rfid'] === $rfid_chauffeur) {
            $firstOdo = (float) $firstItem[6]['odo'];
            $lastOdo = (float) $lastItem[6]['odo'];
        }
        $drive_duration = $data['drives_duration_time'];
        $hour = $drive_duration / 3600;

        $distance = $lastOdo - $firstOdo;
        // $result =  [
        //     'distance' => $distance,
        //     'drive_duration' => (int) $hour
        // ];
        
        return $distance;
    }
}

if(!function_exists('distance_calendar')) {
    function distance_calendar(){
        $infractions = Infraction::with('related_calendar')->whereNotNull('calendar_id')->get();
        foreach($infractions as $item){
            $calendar_start_date = Carbon::parse($item->related_calendar->date_debut);
            $calendar_end_date = $item->related_calendar->date_fin ? Carbon::parse($item->related_calendar->date_fin) : null;

            if ($calendar_end_date === null) {
                $dureeEnHeures = floatval($item->related_calendar->delais_route);
                if ($dureeEnHeures <= 1) {
                    $calendar_end_date = $calendar_start_date->copy()->endOfDay();
                } else {
                    $dureeEnJours = ceil($dureeEnHeures / 24);
                    $calendar_end_date = $calendar_start_date->copy()->addDays($dureeEnJours);
                }
            }
            
            $distance = getDistanceWithImeiAndPeriod($item->rfid, $item->imei, $calendar_start_date, $calendar_end_date);

            $item->distance_calendar = $distance;
            $item->save();
        }
    }
}


if (!function_exists('updateLatAndLongExistingEvent')) {
    function updateLatAndLongExistingEvent($event){
        $formattedDate = $event->date->format('YmdHis');
        
        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=OBJECT_GET_EVENTS,{$event->imei},{$formattedDate},{$formattedDate}";
        $response = Http::timeout(600)->get($url);
        $data = $response->json();


        $latitude = $data[0][5];
        $longitude = $data[0][6];
        
        // Mettre à jour les enregistrements correspondants dans la base de données
        DB::table('event')
            ->where('imei', $event->imei)
            ->where('date', $event->date)
            ->whereNull('latitude')
            ->whereNull('longitude')
            ->update([
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
    }
}


if (!function_exists('updateOdometer')) {
    function updateOdometer($event){
        $formattedDate = $event->date->format('YmdHis');
        
        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=OBJECT_GET_EVENTS,{$event->imei},{$formattedDate},{$formattedDate}";
        $response = Http::timeout(600)->get($url);
        $data = $response->json();
        $odo = (float) $data[0][10]['odo'];
        

        
        // Mettre à jour les enregistrements correspondants dans la base de données
        DB::table('event')
            ->where('imei', $event->imei)
            ->where('date', $event->date)
            ->whereNull('odometer')
            ->update([
                'odometer' => $odo
            ]);
    }
}

if (!function_exists('updateVitesse')) {
    function updateVitesse($event){
        $formattedDate = $event->date->format('YmdHis');
        
        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=OBJECT_GET_EVENTS,{$event->imei},{$formattedDate},{$formattedDate}";
        $response = Http::timeout(600)->get($url);
        $data = $response->json();
        $vitesse =  $data[0][9];
        

        
        // Mettre à jour les enregistrements correspondants dans la base de données
        DB::table('event')
            ->where('imei', $event->imei)
            ->where('date', $event->date)
            ->where('vitesse','=', 0)
            ->update([
                'vitesse' => $vitesse
            ]);
    }
}

if(!function_exists('insertGroupedEventsDetails')){
    function insertGroupedEventsDetails($key, $groupedEvents, $duration)
    {
        // Vérifier si une entrée avec la même clé existe déjà
        $existingEntry = GroupeEvent::where('key', $key)->first();

        // Si aucune entrée avec la même clé n'existe, insérer les données
        if (!$existingEntry) {
            foreach ($groupedEvents as $eventData) {
                GroupeEvent::create([
                    'key' => $key,
                    'imei' => $eventData[2],
                    'type' => $eventData[1],
                    'chauffeur' => $eventData[10]['rfid'],
                    'vehicule' => $eventData[3],
                    'latitude' => $eventData[5],
                    'longitude' => $eventData[6],
                    'duree' => $duration,
                    'description' => $eventData[1],
                ]);
            }
        }
    }
}

if(!function_exists('processEvents')) {
    
    function processEvents($data, $allowedTypes)
    {
        $groupedEvents = [];
        $infractions = [];
        // Parcours du tableau de données
        foreach ($data as $event) {
            // Génération de la clé de groupe
            $groupKey = $event[1] . '_' . $event[2] . '_' . $event[10]['rfid'] . '_' . $event[3] . '_' . substr($event[4], 0, 13);
            // Vérification si un groupe existe déjà pour cet événement
            if (!isset($groupedEvents[$groupKey])) {
                // Création d'un nouveau groupe pour cet événement
                $groupedEvents[$groupKey] = [
                    'events' => [],
                    'lastTimestamp' => strtotime($event[4]),
                ];
            } else {
                // Récupération du dernier timestamp du groupe
                $lastTimestamp = $groupedEvents[$groupKey]['lastTimestamp'];
                // Récupération du timestamp de l'événement actuel
                $currentTimestamp = strtotime($event[4]);

                // Vérification si les événements sont dans la même minute (ou différence de 60 secondes)
                if (abs($currentTimestamp - $lastTimestamp) <= 60) {
                    // Ajout de l'événement au groupe existant
                    $groupedEvents[$groupKey]['events'][] = $event;
                    $groupedEvents[$groupKey]['lastTimestamp'] = $currentTimestamp;
                    continue;
                }
            }
            // Création d'un nouveau groupe pour cet événement
            $groupedEvents[$groupKey]['events'][] = $event;
            $groupedEvents[$groupKey]['lastTimestamp'] = strtotime($event[4]);
            // Si l'événement n'a pas été regroupé, ajouter à la liste des infractions
            $infractions[] = $event;
        }
        // Traitement des groupes pour obtenir les résultats finaux
        $results = [];
        foreach ($groupedEvents as $groupeKey => $group) {
            if (count($group['events']) > 1) { 
                insertGroupedEventsDetails($groupeKey,$group['events'], $allowedTypes[$group['events'][0][1]]);
                // Si le groupe contient plus d'un événement, fusionner les événements en un seul avec la durée appropriée
                $type = $group['events'][0][1];
                $imei = $group['events'][0][2];
                $chauffeur = $group['events'][0][10]['rfid'];
                $vehicule = $group['events'][0][3];
                $odo = $group['events'][0][10]['odo'];
                $latitude = $group['events'][0][5];
                $longitude = $group['events'][0][6];
                $description = $group['events'][0][1];
                // Calcul de la durée totale du groupe
                $duration = (count($group['events']) * 60) + $allowedTypes[$group['events'][0][1]]; 

                // Ajout de l'événement fusionné aux résultats
                $results[] = [
                    'imei' => $imei,
                    'chauffeur' => $chauffeur,
                    'vehicule' => $vehicule,
                    'type' => $type,
                    'date' => $group['events'][0][4], // Utiliser la date du premier événement
                    'odometer' => $odo,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'duree' => $duration,
                    'description' => $description,
                ];
            } else {
                // Si le groupe contient un seul événement, ajouter cet événement aux résultats sans fusion
                $results[] = [
                    'imei' => $group['events'][0][2],
                    'chauffeur' => $group['events'][0][10]['rfid'],
                    'vehicule' => $group['events'][0][3],
                    'type' => $group['events'][0][1],
                    'odometer' => $group['events'][0][10]['odo'],
                    'date' => $group['events'][0][4],
                    'latitude' => $group['events'][0][5],
                    'longitude' => $group['events'][0][6],
                    'duree' => $allowedTypes[$group['events'][0][1]], // Durée par défaut si un seul événement
                    'description' => $group['events'][0][1],
                ];
            }
        }
        return $results;
    }
}



// Récupération des derniers évènements dans l'API M-TEC Tracking et enregistrer dans la table Event
if (!function_exists('getEventFromApi')) {

    function getEventFromApi(){

        $url = 'www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=OBJECT_GET_LAST_EVENTS_7D';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
       
        $penalitesAllowed = Penalite::all()->toArray();
        $allowedTypes = array_column($penalitesAllowed, 'duree','event');
        $filteredData = [];

        // Parcourir les données de l'API
        foreach ($data as $event) {
            if (in_array($event[1], array_keys($allowedTypes)) && isset($event[10]['rfid'])) {
                $filteredData[] = $event;
            }
        }
        // $eventsInsert = processEvents($filteredData, $allowedTypes);
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
                            'odometer' => $item[10]['odo'],
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
                // $existingEvent = Event::where('imei', trim($item['imei']))
                // ->where('date', $item['date'])
                // ->first();

                // Si aucune entrée identique n'existe, insérez les données dans la table Rotation
                // if (!$existingEvent) {
                
                //     if(isset($item['chauffeur']) && $item['chauffeur'] != "0000000000"){
                //         Event::create([
                //             'imei' => $item['imei'],
                //             'chauffeur' => $item['chauffeur'],
                //             'vehicule' => $item['vehicule'],
                //             'type' => trim($item['type']),
                //             'date' => $item['date'],
                //             'odometer' => $item['odometer'],
                //             'latitude' => $item['latitude'],
                //             'longitude' => $item['longitude'],
                //             'duree' => $allowedTypes[$item['type']],
                //             'description' => trim($item['description']),
                //         ]);
                //     }
                // } else {
                //     Event::where('id', $existingEvent->id)
                //     ->update([
                //         'duree' => $allowedTypes[$existingEvent->type],
                //     ]);
                // }
            }
        }
    }    
}

if (!function_exists('RapportPenaliteChauffeurMonthly')){
    function RapportPenaliteChauffeurMonthly(){
        $importExcelRows = ImportExcel::where(function ($query) {
            $query->whereBetween('date_debut', [now()->startOfMonth(), now()->endOfMonth()])
                  ->whereNull('date_fin');
        })
        ->orWhere(function ($query) {
            $query->whereBetween('date_debut', [now()->startOfMonth(), now()->endOfMonth()])
                  ->whereNotNull('date_fin')
                  ->whereBetween('date_fin', [now()->startOfMonth(), now()->endOfMonth()]);
        })
        ->get();
        $events = Event::whereMonth('date', now()->month)->get();
        $distance = 0;

        foreach ($importExcelRows as $importRow) {
                $dateDebut = Carbon::parse($importRow->date_debut);
                $dateFin = $importRow->date_fin ? Carbon::parse($importRow->date_fin) : null;

                if ($dateFin === null) {
                    // Convertir la durée en heures
                    $dureeEnHeures = floatval($importRow->delais_route);
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
                // Récupérer les événements déclenchés pendant cette livraison
                $eventsDuringDelivery = $events->filter(function ($event) use ($dateDebut, $dateFin, $importRow) {
                    $eventDate = Carbon::parse($event->date);
                    // Vérifier si l'événement se trouve dans la plage de dates du début et de fin de livraison
                    $isInfractionInCalendarPeriod = ($dateFin === null) ? $eventDate->eq($dateDebut) : $eventDate->between($dateDebut, $dateFin);
                    
                    // Vérifier si l'IMEI et le camion correspondent à ceux de la ligne d'importation
                    $isMatchingCamion =  strpos($event->vehicule, $importRow->camion) !== false;
                    // Retourner vrai si l'événement est dans la période de livraison et correspond aux IMEI et camion
                    return $isInfractionInCalendarPeriod && $isMatchingCamion;
                });
        
                foreach ($eventsDuringDelivery as $event){
                    $typeEvent = $event->type;
                    // $distance = getDistanceWithImeiAndPeriod($event->chauffeur, $event->imei, $dateDebut, $dateFin);
                    $penalite = Penalite::where('event', $typeEvent)->first();

                    $existingPenalty = PenaliteChauffeur::where([
                        'id_chauffeur' => getIdByRFID($event->chauffeur),
                        'id_calendar' => $importRow->id,
                        'id_event' => $event->id,
                        'id_penalite' => $penalite->id,
                        'date' => $event->date
                    ])->first();

                    // Enregistrer dans la table Penalité chauffeur
                    if(!$existingPenalty &&  strpos($event->vehicule, $importRow->camion) !== false && $event->chauffeur) {
                        insertPenaliteDrive($event, $importRow, $penalite);
                    }  
                }
        }
    }
}

if(!function_exists('checkCalendar')){
    function checkCalendar(){
        // $calendars = ImportExcel::where(function ($query) {
        //     $query->whereBetween('date_debut', [now()->startOfMonth(), now()->endOfMonth()])
        //           ->whereNull('date_fin');
        // })
        // ->orWhere(function ($query) {
        //     $query->whereBetween('date_debut', [now()->startOfMonth(), now()->endOfMonth()])
        //           ->whereNotNull('date_fin')
        //           ->whereBetween('date_fin', [now()->startOfMonth(), now()->endOfMonth()]);
        // })
        // ->get();
        $calendars = ImportExcel::all();
        $infractions = Infraction::whereMonth('date_debut', 4)->whereMonth('date_fin', 4)->get();

        $calendarsInInfractions = [];

        foreach ($calendars as $calendar) {
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

            foreach ($infractionsDuringCalendar as $infraction) {
                $infraction->update([
                    'calendar_id' => $calendar->id,
                ]);
            }
        }
    }
}

if(!function_exists('checkDistance')){
    function checkDistance(){
        // Récupérer le nombre total d'enregistrements avec une distance de 0
        $totalRecords = PenaliteChauffeur::where('distance', '=', 0.00)->where('drive_duration', '=', 0.00)->count();
        $processedRecords = 0;
    
        while ($processedRecords < $totalRecords) {
            // Récupérer les enregistrements où la distance est égale à 0, par lots de 10
            PenaliteChauffeur::where('distance', '=', 0.00)->where('drive_duration', '=', 0.00)->skip($processedRecords)->take(5)->chunk(5, function ($penalites_drivers) use (&$processedRecords) {
                foreach ($penalites_drivers as $item) {
                    $start_date = Carbon::parse($item->related_calendar->date_debut);
                    $end_date = $item->related_calendar->date_fin ? Carbon::parse($item->related_calendar->date_fin) : null;
                    $imei = $item->related_event->imei;
                    $rfid = $item->related_event->chauffeur;
                    $event_date = Carbon::parse($item->related_event->date);
    
                    if ($end_date === null) {
                        $dureeEnHeures = floatval($item->related_calendar->delais_route);
                        if ($dureeEnHeures <= 1) {
                            $end_date = $start_date->copy()->endOfDay();
                        } else {
                            $dureeEnJours = ceil($dureeEnHeures / 24);
                            $end_date = $start_date->copy()->addDays($dureeEnJours);
                        }
                    }
    
                    $itemDistance = getDistanceWithImeiAndPeriod($rfid, $imei, $start_date, $end_date);
    
                    // Mettre à jour la distance seulement si elle est différente de 0
                    if ($itemDistance != 0) {
                        DB::table('penalite_chauffeur')
                            ->where('id', $item->id)
                            ->update(['distance' => $itemDistance['distance'], 'drive_duration' => $itemDistance['drive_duration']]);
                    }
                    
                    // Incrémenter le nombre d'enregistrements traités
                    $processedRecords++;
                }
            });
        }
    }
}

if(!function_exists('checkTempsConduiteMinJourTravail')){
    function checkTempsConduiteMinJourTravail(){
        // Récupérer toutes les pénalités
        $penalites = PenaliteChauffeur::all();
        $limite = 0;
        $infraction = null;
        $id_penalite = null;

        // Tableau pour stocker les heures de conduite pour chaque chauffeur
        $dataToInsert = [];

        // Parcourir chaque pénalité
        foreach ($penalites as $penalite) {
            $idChauffeur = $penalite->id_chauffeur;
            $dateDebut = Carbon::parse($penalite->related_calendar->date_debut);
            $dateFin = $penalite->related_calendar->date_fin ? Carbon::parse($penalite->related_calendar->date_fin) : null;
            $delaisRoute = $penalite->related_calendar->delais_route;
          
            if (is_null($dateFin)) {
                if ($delaisRoute <= 1) {
                    // Si la date de début est pendant la journée, ajouter le délai de route à 22h, sinon ajouter à 4h pour la nuit
                    $heureDebut = $dateDebut->hour;
                    if ($heureDebut >= 4 && $heureDebut < 22) {
                        $dateFin = $dateDebut->copy()->setHour(22)->startOfHour(); // Fin de la journée à 22h
                    } else {
                        $dateFin = $dateDebut->copy()->addDay()->setHour(4)->startOfHour(); // Début de la journée suivante à 4h
                    }
                } else {
                    $dateFin = $dateDebut->copy()->addHours($delaisRoute)->startOfHour(); // Ajouter le délai de route à la date de début
                }
            }

            $heureDebut = $dateDebut->format('H:i:s');
            $heureFin = $dateFin->format('H:i:s');
            // $dureeTrajet = Carbon::parse($heureDebut)->diffInMinutes(Carbon::parse($heureFin));
            // $heureTrajet = $dureeTrajet / 60;
            // $dureeTrajet = getDistanceWithImeiAndPeriod($penalite->related_driver->rfid, $penalite->related_event->imei, $dateDebut, $dateFin);
            // $heureTrajet = $dureeTrajet['drive_duration'] / 3600;
            $heureTrajet = $penalite->drive_duration / 3600;
        
            if (($heureDebut >= '04:00:00' && $heureFin <= '22:00:00')) {
                // Règle de jour
                $limite = 13;
            } elseif ($heureDebut >= '22:00:00' || $heureFin <= '04:00:00') {
                // Règle de nuit
                $limite = 12;
            } elseif (($heureDebut < '04:00:00' && $heureFin > '22:00:00') || ($heureDebut < '04:00:00' && $heureFin < '22:00:00')) {
                // Le trajet chevauche la journée et la nuit
                $limite = 12;
            } 

            if($heureTrajet > $limite){
                $infraction = Penalite::where('event', 'Temps de conduite maximum dans une journée de travail')->first();
                //Enregistrer dans la table Event 
                $event = Event::updateOrCreate([
                    'imei' => $penalite->related_event->imei,
                    'chauffeur' => $penalite->related_event->rfid,
                    'vehicule' => $penalite->related_event->vehicule,
                    'type' => 'Temps de conduite maximum dans une journée de travail',
                    'description' => 'Temps de conduite maximum dans une journée de travail',
                    'latitude' => $penalite->related_event->latitude,
                    'longitude' => $penalite->related_event->longitude,
                    'duree' => $dureeTrajet['drive_duration'],
                    'date' => $penalite->date,
                ]);

                // $existingItem = Model::where('column', $item['value'])->first();
                $existingPenalty = PenaliteChauffeur::where([
                    'id_chauffeur' => $penalite->id_chauffeur,
                    'id_calendar' => $penalite->id_calendar,
                    'id_event' => $event->id,
                    'id_penalite' => $infraction->id,
                    'date' => $event->date,
                    'distance' => $penalite->distance,
                    'duree' => $dureeTrajet['drive_duration']
                ])->first();

                if (!$existingPenalty) {
                    $dataToInsert[] = [
                        'id_chauffeur' => $penalite->id_chauffeur,
                        'id_calendar' => $penalite->id_calendar,
                        'id_event' => $event->id,
                        'id_penalite' => $infraction->id,
                        'date' => $event->date,
                        'distance' => $penalite->distance,
                        'duree' => $dureeTrajet['drive_duration']
                    ];
                }
            }
            
        }
        if(count($dataToInsert) > 0){
            PenaliteChauffeur::insert($dataToInsert);
        }
    
    }
}

if(!function_exists('v_infraction')){
    function v_infraction($imei, $chauffeur, $type){
        $results = DB::table('event')
        ->select('imei', 'chauffeur', 'vehicule', 'type', 'odometer', 'latitude', 'longitude', DB::raw("LEFT(date,10) as simple_date"), DB::raw("RIGHT(date,8) as heure"), 'date as date_heure')
        ->where('imei', '=', $imei)
        ->where('chauffeur', '=', $chauffeur)
        ->where('type', '=', $type)
        ->orderBy('heure', 'ASC')
        ->get();
        
        return $results;
    }
}

if (!function_exists('checkInfraction')) {
    function checkInfraction()
    {
        $records = DB::table('event')
        ->select('imei', 'chauffeur', 'vehicule', 'type', 'odometer','vitesse', 'latitude', 'longitude', DB::raw("LEFT(date,10) as simple_date"), DB::raw("RIGHT(date,8) as heure"), 'date as date_heure')
        ->orderBy('simple_date', 'ASC')
        ->orderBy('heure', 'ASC')->get();
        
        $results = [];
        $prevRecord = null;
        $firstValidRecord = null;
        $lastValidRecord = null;
        $maxSpeed = 0;

        foreach ($records as $record) {
            if(trim($record->type) === "Accélération brusque" || trim($record->type) === "Freinage brusque"){
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
                    'point' => 1,
                    'insuffisance' => 0
                ];
            }else{
            
                if ($firstValidRecord === null) {
                    $firstValidRecord = $record;
                    $maxSpeed = $record->vitesse;
                }

                // Vérifier s'il y a un enregistrement précédent
                if ($prevRecord !== null) {
                    // Comparer les attributs chauffeur, véhicule et date sans tenir compte de l'heure
                    if ($record->chauffeur === $prevRecord->chauffeur &&
                        $record->vehicule === $prevRecord->vehicule &&
                        $record->simple_date === $prevRecord->simple_date && trim($record->type) === trim($prevRecord->type)) {
                        // Convertir les dates en objets DateTime pour faciliter la comparaison
                        $prevDate = new DateTime($prevRecord->date_heure);
                        $currentDate = new DateTime($record->date_heure);
                        $tolerence = Penalite::where('event','=', $record->type)->first();
                        // Calculer la différence en secondes
                        $differenceSeconds = $currentDate->getTimestamp() - $prevDate->getTimestamp();

                        if ($differenceSeconds === $tolerence->param) {
                            // Si l'intervalle est de 60 secondes, continuer à traiter les enregistrements
                            // Mettre à jour le dernier enregistrement valide
                            if ($record->vitesse > $maxSpeed) {
                                $maxSpeed = $record->vitesse; // Mettre à jour la vitesse maximale si la vitesse actuelle est plus grande
                            }
                            $lastValidRecord = $record;
                        } else {
                            // Si l'intervalle n'est pas de 60 secondes, réinitialiser les enregistrements valides
                            if ($firstValidRecord !== null && $lastValidRecord !== null) {
                                $results[] = groupedInfraction($firstValidRecord, $prevRecord, $maxSpeed);
                            }
                            $firstValidRecord = $record;
                            $lastValidRecord = null;
                            $maxSpeed = $record->vitesse; 
                        }
                    } else {
                        // Si les attributs chauffeur, véhicule ou date sont différents, réinitialiser les enregistrements valides
                        if ($firstValidRecord !== null && $lastValidRecord !== null) {
                            $results[] = groupedInfraction($firstValidRecord, $prevRecord, $maxSpeed);
                        }
                        $firstValidRecord = $record;
                        $lastValidRecord = null;
                        $maxSpeed = $record->vitesse;
                    }
                }
                // Mettre à jour l'enregistrement précédent
                $prevRecord = $record;
            }
        }
        // Ajouter le dernier groupe d'infractions
        if ($firstValidRecord !== null && $lastValidRecord !== null) {
            $results[] = groupedInfraction($firstValidRecord, $prevRecord, $maxSpeed);
        }

        return $results;
    }
}

if(!function_exists('saveInfraction')){
    function saveInfraction(){
        $infractions = checkInfraction();
        foreach($infractions as $item){
            $existingInfraction = Infraction::where('imei', $item['imei'])
                    ->where('rfid', $item['chauffeur'])
                    ->where('event', $item['type'])
                    ->where('date_debut', $item['date_debut'])
                    ->where('date_fin', $item['date_fin'])
                    ->where('heure_debut', $item['heure_debut'])
                    ->where('heure_fin', $item['heure_fin'])
                    ->first();
    
            if (!$existingInfraction) {
            
                if(isset($item['chauffeur']) && $item['chauffeur'] != "0000000000"){
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
        }
    }
}


if(!function_exists('groupedInfraction')){
    function groupedInfraction($firstRecord, $lastRecord, $maxvitesse){
        $firstDate = new DateTime($firstRecord->date_heure);
        $lastDate = new DateTime($lastRecord->date_heure);
        $differenceSeconds = $lastDate->getTimestamp() - $firstDate->getTimestamp();
        $distance = $lastRecord->odometer - $firstRecord->odometer;
        $tolerence = Penalite::where('event','=',$firstRecord->type)->first();
        

        return [
            'imei' => $firstRecord->imei,
            'chauffeur' => $firstRecord->chauffeur,
            'vehicule' => $firstRecord->vehicule,
            'type' => $firstRecord->type,
            'distance' => $distance,
            'odometer' => $lastRecord->odometer,
            'vitesse' => $maxvitesse,
            'duree_infraction' => ($differenceSeconds + $tolerence->default_value), 
            'duree_initial' => $tolerence->default_value, 
            'date_debut' => $firstRecord->simple_date,
            'date_fin' => $lastRecord->simple_date,
            'heure_debut' => $firstRecord->heure,
            'heure_fin' => $lastRecord->heure,
            'date_heure_debut' => $firstRecord->date_heure,
            'date_heure_fin' => $lastRecord->date_heure,
            'gps_debut' => $firstRecord->latitude . ',' . $firstRecord->longitude,
            'gps_fin' => $lastRecord->latitude . ',' . $lastRecord->longitude,
            'point' => (($differenceSeconds + $tolerence->default_value) * $tolerence->point_penalite) / $tolerence->default_value,
            'insuffisance' => 0
        ];
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



