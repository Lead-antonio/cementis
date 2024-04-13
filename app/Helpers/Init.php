<?php

use App\Models\ImportExcel;
use App\Models\Rotation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Chauffeur;
use App\Models\Penalite;
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
        $results = DB::table('penalite_chauffeur as pc')
            ->join('chauffeur as ch', 'pc.id_chauffeur', '=', 'ch.id')
            ->join('penalite as p', 'pc.id_penalite', '=', 'p.id')
            ->join('event as e', 'pc.id_event', '=', 'e.id')
            ->select(
                'ch.nom as driver',
                DB::raw("DATE_FORMAT(pc.date, '%Y-%m') as Month"),
                DB::raw("DATE_FORMAT(pc.date, '%Y-%m-%d %H:%i:%s') as date_event"),
                'e.type as event',
                'p.point_penalite as penalty_point',
                'pc.distance as distance',
                DB::raw("(p.point_penalite * 100) / pc.distance as score_card")
            )
            ->groupBy('ch.nom', DB::raw("DATE_FORMAT(pc.date, '%Y-%m')"), DB::raw("DATE_FORMAT(pc.date, '%Y-%m-%d %H:%i:%s')"), 'e.type', 'p.point_penalite', 'pc.distance')
            ->orderBy('ch.nom')
            ->orderBy(DB::raw("DATE_FORMAT(pc.date, '%Y-%m-%d %H:%i:%s')"))
            ->get();

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

if (!function_exists('insertPenaliteDrive')) {
    function insertPenaliteDrive($event, $calendar, $penalite, $distance){
            PenaliteChauffeur::updateOrCreate([
                'id_chauffeur' => getIdByRFID($event->chauffeur),
                'id_calendar' => $calendar->id,
                'id_event' => $event->id,
                'id_penalite' => $penalite->id,
                'distance' => $distance,
                'date' => $event->date,
            ], [
                'distance' => $distance,
            ]);
    }
}

if (!function_exists('getDistanceWithImeiAndPeriod')) {

    function getDistanceWithImeiAndPeriod($rfid_chauffeur, $imei_vehicule, $start_date, $end_date){
        // Formatage des dates au format YYYYMMDD
        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=0AFEAB2328492FB8118E37ECCAF5E79F&cmd=OBJECT_GET_ROUTE,".$imei_vehicule.",".$start_date->format('YmdHis').",".$end_date->format('YmdHis').",20";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        $firstOdo = null;
        $lastOdo = null;

        foreach ($data["route"] as $record) {
            // Vérifiez si la clé "odo" est présente dans le sous-tableau et si le RFID correspond
            if (isset($record[6]['odo']) && isset($record[6]['rfid']) && $record[6]['rfid'] === $rfid_chauffeur) {
                $odo = (float) $record[6]['odo']; // Convertir en flottant pour manipulation
                // Si c'est le premier enregistrement trouvé, définissez $firstOdo
                if ($firstOdo === null) {
                    $firstOdo = $odo;
                }
                // Toujours mettre à jour $lastOdo pour obtenir le dernier enregistrement trouvé
                $lastOdo = $odo;
            }
        }

        $distance = $lastOdo - $firstOdo;
        return $distance;
    }
}



// Récupération des derniers évènements dans l'API M-TEC Tracking et enregistrer dans la table Event
if (!function_exists('getEventFromApi')) {

    function getEventFromApi(){

        $url = 'www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=OBJECT_GET_LAST_EVENTS_7D';

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

                $penaltyEvent = Penalite::where('event', $item[1])->first();
                // Si aucune entrée identique n'existe, insérez les données dans la table Rotation
                if (!$existingEvent && $penaltyEvent) {
                    if(isset($item[10]['rfid']) && $item[10]['rfid'] != "0000000000"){
                        Event::create([
                            'imei' => $item[2],
                            'chauffeur' => $item[10]['rfid'],
                            'vehicule' => $item[3],
                            'type' => $item[1],
                            'date' => $item[4],
                            'description' => $item[1],
                        ]);
                    }
                }
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
                    $isEventInDeliveryPeriod = ($dateFin === null) ? $eventDate->eq($dateDebut) : $eventDate->between($dateDebut, $dateFin);
                    // Vérifier si l'IMEI et le camion correspondent à ceux de la ligne d'importation
                    $isMatchingIMEIAndCamion = $importRow->imei === $event->imei && $importRow->camion === $event->vehicule;
                    // Retourner vrai si l'événement est dans la période de livraison et correspond aux IMEI et camion
                    return $isEventInDeliveryPeriod && $isMatchingIMEIAndCamion;
                });
        
                foreach ($eventsDuringDelivery as $event){
                    $typeEvent = $event->type;
                    $distance = getDistanceWithImeiAndPeriod($event->chauffeur, $event->imei, $importRow->date_debut, $importRow->date_fin);
                    $penalite = Penalite::where('event', $typeEvent)->first();

                    $existingPenalty = PenaliteChauffeur::where([
                        'id_chauffeur' => getIdByRFID($event->chauffeur),
                        'id_calendar' => $importRow->id,
                        'id_event' => $event->id,
                        'id_penalite' => $penalite->id,
                        'date' => $event->date
                    ])->first();

                    // Enregistrer dans la table Penalité chauffeur
                    if(!$existingPenalty && $importRow->imei === $event->imei && $importRow->camion === $event->vehicule) {
                        insertPenaliteDrive($event, $importRow, $penalite, $distance);
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



