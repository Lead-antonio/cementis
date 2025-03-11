<?php

namespace App\Http\Controllers;

use App\Models\Transporteur;
use Illuminate\Http\Request;
use App\Models\Vehicule;
use App\Models\Chauffeur;
use App\Models\ImportExcel;
use App\Models\Importcalendar;
use App\Models\Scoring;
use App\Repositories\DashboardRepository;
use Illuminate\Support\Facades\DB;

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
        $structuredData = [];
        $totalVehicules = Vehicule::count();
        $totalTransporteurs = Transporteur::count();
        $totalChauffeurs = Chauffeur::count();
        $selectedPlanning = $request->selectedPlanning ?? DB::table('import_calendar')->latest('id')->value('id');
        $data = $this->dashboardRepository->GetData();
        $data['import_calendar'] = $import_calendar = Importcalendar::all();
        $data['selectedPlanning'] = $selectedPlanning;
        $data['totalVehicules'] = $totalVehicules;
        $data['totalTransporteurs'] = $totalTransporteurs;
        $data['totalChauffeurs'] = $totalChauffeurs;
        $data['transporteurData'] = json_encode($structuredData);

        $data['best_scoring'] = getAllGoodScoring($selectedPlanning);
        $data['bad_scoring'] = getAllBadScoring($selectedPlanning);
        
        $data['driver_has_score'] = $this->count_driver_has_scoring($selectedPlanning);
        $data['driver_not_has_score'] = $this->count_driver_not_has_scoring($selectedPlanning);  
        $data['driver_not_fix'] = $this->driver_not_fix();
        $data['driver_in_calendar'] = $this->count_driver_in_calendar($selectedPlanning);
        
        if ($request->ajax()) {
            return response()->json([
                'driver_has_score' => $data['driver_has_score'],
                'driver_not_has_score' => $data['driver_not_has_score'],
                'driver_in_calendar' => $data['driver_in_calendar'],
                'best_scoring' => view('dashboard.best_scoring', ['best_scoring' => $data['best_scoring'], 'selectedPlanning' => $selectedPlanning])->render(),
                'bad_scoring' => view('dashboard.bad_scoring', ['bad_scoring' => $data['bad_scoring'], 'selectedPlanning' => $selectedPlanning])->render(),
                
            ]);
        }
        
        return view('dashboard.index', $data);
    }

    public function count_driver_has_scoring($id_planning)
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

    public function count_driver_not_has_scoring($id_planning)
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


    
    public function count_driver_in_calendar($id_planning)
    {
        $importTrucks = ImportExcel::where('import_calendar_id', $id_planning)
        ->distinct()
        ->pluck('camion')
        ->map(function ($camion) {
            return strpos($camion, ' - ') !== false ? explode(' - ', $camion)[0] : $camion;
        })
        ->unique() // Supprime les doublons après transformation
        ->toArray();

        return count($importTrucks);
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
}
