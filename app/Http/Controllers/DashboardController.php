<?php

namespace App\Http\Controllers;

use App\Models\Transporteur;
use Illuminate\Http\Request;
use App\Models\Vehicule;
use App\Models\Chauffeur;
use App\Models\ImportExcel;
use App\Models\Importcalendar;
use App\Models\ScoreDriver;
use App\Models\Scoring;
use App\Repositories\DashboardRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /** @var  DashboardRepository */
    private $dashboardRepository;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DashboardRepository $dashboardRepo)
    {
        $this->dashboardRepository = $dashboardRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userName = Auth::user()->name;
        $transporteurs_id = Transporteur::where('nom', 'like', '%' . $userName . '%')->value('id');
        $selectedPlanning = $request->selectedPlanning ?? DB::table('import_calendar')->latest('id')->value('id');
        $selectedTransporteur = $request->selectedTransporteur ?? $transporteurs_id ?? null;
        $totalVehicules = DB::table('vehicule')
            ->when($selectedPlanning, function($query) use ($selectedPlanning) {
                    $query->where('id_planning', $selectedPlanning);
                })
            ->when($selectedTransporteur, function($query) use ($selectedTransporteur) {
                $query->where('id_transporteur', $selectedTransporteur);
            })
            ->count();
        $totalTransporteurs = Transporteur::count();
        $totalChauffeurs = DB::table('chauffeur')
                ->when($selectedPlanning, function($query) use ($selectedPlanning) {
                    $query->where('id_planning', $selectedPlanning);
                })
                ->when($selectedTransporteur, function($query) use ($selectedTransporteur) {
                    $query->where('transporteur_id', $selectedTransporteur);
                })
                ->count();
        $data = $this->dashboardRepository->GetData();
        $data['import_calendar'] =  Importcalendar::all();
        $data['transporteurs'] =  Transporteur::all();
        $data['selectedPlanning'] = $selectedPlanning;
        $data['selectedTransporteur'] = $selectedTransporteur;
        $data['totalVehicules'] = $totalVehicules;
        $data['totalTransporteurs'] = $totalTransporteurs;
        $data['totalChauffeurs'] = $totalChauffeurs;


        $data['best_scoring'] = $this->get_top_3_score($selectedPlanning, $selectedTransporteur);
        $data['bad_scoring'] = $this->get_worst_3_score($selectedPlanning, $selectedTransporteur);
        $data['match_rfid'] = $this->getRfidMatchingStats($selectedPlanning, $selectedTransporteur);
        
        $data['driver_has_score'] = $this->driver_has_scoring($selectedPlanning, $selectedTransporteur);
        $data['driver_not_has_score'] = $this->driver_not_have_scoring($selectedPlanning);  
        $data['truck_in_calendar'] = $this->count_truck_in_calendar($selectedPlanning, $selectedTransporteur);
        $data['drivers_badge_in_calendars'] = $this->count_badge_number_in_calendar($selectedPlanning, $selectedTransporteur);
        $data['score_zero'] = $this->countDriverScoreWithPointZero($selectedPlanning, $selectedTransporteur);
        $data['score_zero_more_than_3_planning'] = $this->countMoreThan3TrajectScoreWithPointZero($selectedPlanning, $selectedTransporteur);
        $data['vehicule_transporteur'] = Transporteur::withCount('vehicule')->orderByDesc('vehicule_count')->get();
        $data['driver_transporteur'] = Transporteur::withCount('chauffeurs')->orderByDesc('chauffeurs_count')->get();

        
        if ($request->ajax()) {
            return response()->json([
                'driver_has_score' => $data['driver_has_score'],
                'driver_not_has_score' => $data['driver_not_has_score'],
                'truck_in_calendar' => $data['truck_in_calendar'],
                'total_chauffeur' => $data['totalChauffeurs'],
                'total_vehicule' => $data['totalVehicules'],
                'match_rfid' => $data['match_rfid'],
                'score_zero' => $data['score_zero'],
                'score_zero_more_than_3_planning' => $data['score_zero_more_than_3_planning'],
                'drivers_badge_in_calendars' => $data['drivers_badge_in_calendars'],
                'best_scoring' => view('dashboard.best_scoring', ['best_scoring' => $data['best_scoring'], 'selectedPlanning' => $selectedPlanning])->render(),
                'bad_scoring' => view('dashboard.bad_scoring', ['bad_scoring' => $data['bad_scoring'], 'selectedPlanning' => $selectedPlanning])->render(),
            ]);
        }
        
        return view('dashboard.index', $data);
    }

    public function count_truck_has_scoring($id_planning)
    {
        $query = Scoring::where('id_planning', $id_planning);
        $camionsImport = ImportExcel::where('import_calendar_id', $id_planning)
                        ->distinct()
                        ->pluck('camion') // Récupère uniquement la colonne "camion"
                        ->unique()
                        ->toArray();

        $scoring_trucks = Scoring::where('id_planning', $id_planning)->pluck('camion')->unique()->toArray();
        $clean_trucks = array_map(function($entry) {
            preg_match('/\b[0-9]{3,}[A-Z]+\b/', $entry, $matches);
            return $matches[0] ?? $entry;
        }, $scoring_trucks);         
        
        $common_matricules = array_intersect($camionsImport, $clean_trucks);
        return count($common_matricules); 
    }

    public function count_truck_not_has_scoring($id_planning)
    {
        // Récupérer les camions uniques depuis ImportExcel
        $importTrucks = ImportExcel::where('import_calendar_id', $id_planning)
            ->distinct()
            ->pluck('camion')
            ->map(function ($camion) {
                return strpos($camion, ' - ') !== false ? explode(' - ', $camion)[0] : $camion;
            })
            ->unique()
            ->toArray();

        $importTrucks = array_map('trim', $importTrucks);


        $scoringTrucks = Scoring::where('id_planning', $id_planning)->pluck('camion')->unique()->toArray();
        
        $matricules = array_map(function($entry) {
            preg_match('/\b[0-9]{3,}[A-Z]+\b/', $entry, $matches);
            return $matches[0] ?? $entry;
        }, $scoringTrucks);

        $common_matricules = array_intersect($importTrucks, $matricules);

        $result = [];

        foreach ($importTrucks as $value) {
            if (!in_array($value, array_unique($common_matricules))) {
                $result[] = $value;
            }
        }

        return count(array_unique($result));
    }

    public function getRfidMatchingStats($id_planning, $id_transporteur = null){
        return DB::table('scoring')
            ->when($id_transporteur, function ($query, $id_transporteur) {
                $query->where('transporteur_id', $id_transporteur);
            })
            ->where('id_planning', $id_planning)
            ->selectRaw('
                COUNT(*) AS total_rows,
                SUM(CASE 
                        WHEN badge_rfid = badge_calendar 
                        THEN 1 ELSE 0 
                    END) AS match_count,
                SUM(CASE 
                        WHEN badge_rfid != badge_calendar 
                            OR badge_rfid IS NULL 
                            OR badge_calendar IS NULL 
                        THEN 1 ELSE 0 
                    END) AS non_match_count,
                ROUND(
                    100.0 * SUM(CASE 
                        WHEN badge_rfid = badge_calendar 
                        THEN 1 ELSE 0 
                    END) / COUNT(*), 2
                ) AS match_percentage,
                ROUND(
                    100.0 * SUM(CASE 
                        WHEN badge_rfid != badge_calendar 
                            OR badge_rfid IS NULL 
                            OR badge_calendar IS NULL 
                        THEN 1 ELSE 0 
                    END) / COUNT(*), 2
                ) AS non_match_percentage
            ')
            ->first();
    }


    public function count_truck_in_calendar($id_planning, $id_transporteur = null)
    {
        $importTrucks = ImportExcel::where('import_calendar_id', $id_planning)
        ->distinct()
        ->pluck('camion')
        ->map(function ($camion) {
            return strpos($camion, ' - ') !== false ? explode(' - ', $camion)[0] : $camion;
        })
        ->unique()
        ->toArray();

        if (is_null($id_transporteur)) {
            return count($importTrucks);
        }

        $matricules_transporteur = Vehicule::where('id_transporteur', $id_transporteur)
            ->where('id_planning', $id_planning)
            ->pluck('nom')
            ->toArray();

        $camions_filtres = array_intersect($importTrucks, $matricules_transporteur);

        return count($camions_filtres);
    }

    public function driver_not_fix(){
        $repartitionChauffeurs = Transporteur::select('transporteur.nom', 'chauffeur.transporteur_id', \DB::raw('COUNT(*) as nombre_chauffeurs_non_fixes'))
            ->join('chauffeur', 'chauffeur.transporteur_id', '=', 'transporteur.id')
            ->where('chauffeur.nom', 'chauffeur non fixe')
            ->groupBy('chauffeur.transporteur_id', 'transporteur.nom')
            ->orderByDesc('nombre_chauffeurs_non_fixes')
            ->get();

        return $repartitionChauffeurs;
    }

    public function driver_has_scoring($id_planning, $transporteur_id){
         // Récupérer les camions uniques depuis ImportExcel
         $badge_calendars = ImportExcel::where('import_calendar_id', $id_planning)
         ->distinct()
         ->pluck('badge_chauffeur')
         ->unique()
         ->toArray();

        $badge_calendars = array_map('trim', $badge_calendars);

        $scoringBadge = Scoring::
            when($transporteur_id, function ($query, $transporteur_id) {
                return $query->where('transporteur_id', $transporteur_id);
            })
            ->where('id_planning', $id_planning)
            ->pluck('badge_calendar')->toArray();
            

        $compteur = 0;

        foreach ($badge_calendars as $badge) {
            if (in_array($badge, $scoringBadge)) {
                $compteur++;
            }
        }

        

        return $compteur;
    }

    public function driver_not_have_scoring($id_planning){
        // Récupérer les badges des chauffeurs depuis ImportExcel
        $badge_calendars = ImportExcel::where('import_calendar_id', $id_planning)
        ->distinct()
        ->pluck('badge_chauffeur')
        ->unique()
        ->toArray();

        $badge_calendars = array_map('trim', $badge_calendars);

        $scoringBadge = Scoring::where('id_planning', $id_planning)
            ->with('driver.latest_update')
            ->get();

        $badges_scoring = $scoringBadge->map(function($scoring) {
            return $scoring->badge_calendar;
        })->toArray();

        $badge_not_in_scoring = array_diff($badges_scoring, $badge_calendars);
        
        return count($badge_not_in_scoring);

    }

    public function count_badge_number_in_calendar($id_planning, $transporteur_id = null){
        $drivers_badge_in_calendars = ImportExcel::where('import_calendar_id', $id_planning)
            ->distinct()
            ->pluck('badge_chauffeur')
            ->filter()
            ->unique()
            ->toArray();
        
        if (is_null($transporteur_id)) {
            return count($drivers_badge_in_calendars) ?? 0;
        }
           
        $driver_badge_transporteur = Chauffeur::where('transporteur_id', $transporteur_id)
            ->where('id_planning', $id_planning)
            ->pluck('numero_badge')
            ->toArray();

        $driver_badge_filtres = array_intersect($drivers_badge_in_calendars, $driver_badge_transporteur);

        return count($driver_badge_filtres);
    }

    public function countDriverScoreWithPointZero($id_planning, $transporteur_id)
    {
        // Exécuter la requête avec Query Builder
        $count = ScoreDriver::when($transporteur_id, function ($query, $transporteur_id) {
                        return $query->where('transporteur', $transporteur_id);
                    })
        ->where('id_planning', $id_planning)
        ->where('score', 0)
        ->count('badge');

        return $count;
    }

    public function countMoreThan3TrajectScoreWithPointZero($id_planning, $transporteur_id)
    {
        // Exécuter la requête avec Query Builder
        $subQuery = DB::table('import_excel as ie')
            ->join('score_driver as s', 'ie.badge_chauffeur', '=', 's.badge')
            ->where('s.score', 0)
            ->where('s.id_planning', $id_planning)
            ->when($transporteur_id, function ($query, $transporteur_id) {
                return $query->where('transporteur', $transporteur_id);
            })
            ->where('ie.import_calendar_id', $id_planning)
            ->groupBy('ie.badge_chauffeur')
            ->havingRaw('COUNT(ie.id) >= 3')
            ->select('ie.camion');

        // Requête principale : compter le nombre total de camions dans la sous-requête
        $count = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery) // ✅ nécessaire pour inclure les bindings (sécurisé)
            ->count();
        
        return $count;
    }

    public function get_top_3_score($id_planning, $transporteur_id){
        $top = ScoreDriver::when($transporteur_id, function ($query, $transporteur_id) {
                        return $query->where('transporteur', $transporteur_id);
                    })
                ->where('id_planning', $id_planning)
                ->orderBy('score')->limit(3)
                ->get();
        
        return $top;
    }

    public function get_worst_3_score($id_planning, $transporteur_id){
        $worst = ScoreDriver::when($transporteur_id, function ($query, $transporteur_id) {
                return $query->where('transporteur', $transporteur_id);
            })
            ->where('id_planning', $id_planning)
            ->orderByDesc('score')->limit(3)
            ->get();

        return $worst;
    }
}
