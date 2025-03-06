<?php

namespace App\Http\Controllers;

use App\Models\Transporteur;
use Illuminate\Http\Request;
use App\Models\Vehicule;
use App\Models\Chauffeur;
use App\Models\ImportExcel;
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
    public function index()
    {
        $structuredData = [];
        $totalVehicules = Vehicule::count();
        $totalTransporteurs = Transporteur::count();
        $totalChauffeurs = Chauffeur::count();
        $selectedPlanning = DB::table('import_calendar')->latest('id')->value('id');
        $data = $this->dashboardRepository->GetData();
        $data['totalVehicules'] = $totalVehicules;
        $data['totalTransporteurs'] = $totalTransporteurs;
        $data['totalChauffeurs'] = $totalChauffeurs;
        $data['transporteurData'] = json_encode($structuredData);

        $data['best_scoring'] = getAllGoodScoring();
        $data['bad_scoring'] = getAllBadScoring();

        $data['driver_has_score'] = $this->count_driver_has_scoring($selectedPlanning);
        $data['driver_not_has_score'] = $this->count_driver_not_has_scoring($selectedPlanning);  
        $data['driver_not_fix'] = $this->driver_not_fix();
        $data['driver_in_calendar'] = $this->count_driver_in_calendar($selectedPlanning);
        $data['selectedPlanning'] = $selectedPlanning;
        
        return view('dashboard.index', $data);
    }

    public function count_driver_has_scoring($id_planning)
    {
        $query = Scoring::where('id_planning', $id_planning);
        $camionsImport = ImportExcel::where('import_calendar_id', $id_planning)
                        ->pluck('camion') // Récupère uniquement la colonne "camion"
                        ->toArray();
        $countDriver = Scoring::where('id_planning', $id_planning)
        ->where(function ($q) use ($camionsImport) {
            foreach ($camionsImport as $camion) {
                $q->orWhere('camion', 'LIKE', "%{$camion}%");
            }
        })
        ->count();

        return $countDriver; // Nombre de chauffeur uniques ayant un score
    }


    public function count_driver_not_has_scoring($id_planning)
    {
        $importTrucks = ImportExcel::where('import_calendar_id', $id_planning)
        ->distinct()
        ->pluck('camion')
        ->map(function ($camion) {
            return strpos($camion, ' - ') !== false ? explode(' - ', $camion)[0] : $camion;
        })
        ->unique() // Supprime les doublons après transformation
        ->toArray();

        // Récupérer tous les camions de scoring pour ce planning
        $scoringTrucks = Scoring::where('id_planning', $id_planning)
            ->pluck('camion')
            ->map(function($camion) {
                return strpos($camion, ' - ') !== false ? explode(' - ', $camion)[0] : $camion;
            })
            ->toArray();

        // Trouver les camions dans import_excel qui ne sont pas dans scoring
        $missingTrucks = array_diff($importTrucks, $scoringTrucks);

        return count(array_values($missingTrucks));
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
