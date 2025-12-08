<?php

namespace App\Http\Controllers;

use Flash;
use App\DataTables\VehiculeDataTable;
use App\DataTables\TrucknotscoringDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateVehiculeRequest;
use App\Http\Requests\UpdateVehiculeRequest;
use App\Repositories\VehiculeRepository;
use App\Models\Transporteur;
use Illuminate\Http\Request;
use App\Models\Vehicule;
use App\Models\Chauffeur;
use App\Models\ImportExcel;
use App\Models\Scoring;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use App\Models\VehiculeUpdate;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Importcalendar;

class VehiculeController extends AppBaseController
{
    /** @var VehiculeRepository $vehiculeRepository*/
    private $vehiculeRepository;

    public function __construct(VehiculeRepository $vehiculeRepo)
    {
        $this->vehiculeRepository = $vehiculeRepo;
    }

    /**
     * Display a listing of the Vehicule.
     *
     * @param VehiculeDataTable $vehiculeDataTable
     *
     * @return Response
     */
    public function index(VehiculeDataTable $vehiculeDataTable,  Request $request)
    {
        $transporteur_id = null;
        if(Auth::user()->roles->first()->name === "transporteur"){
            $userName = Auth::user()->name;
            $transporteurs_id = Transporteur::where('nom', 'like', '%' . $userName . '%')->value('id');
        }
        
        $plannings = Importcalendar::all();
        $selected_planning =  $request->input('id_planning') ?? DB::table('import_calendar')->latest('id')->value('id');
        $selectedTransporteur = request()->input('selectedTransporteur') ?? $transporteurs_id ?? null;
        $vehiculeDataTable->setSelectedTransporteur($selectedTransporteur);
        $vehiculeDataTable->setSelectedPlanning($selected_planning);

        return $vehiculeDataTable->render('vehicules.index', [
            'selectedTransporteur' => $selectedTransporteur,
        ], compact('plannings', 'selected_planning'));
    }

    /**
     * Show the form for creating a new Vehicule.
     *
     * @return Response
     */
    public function create()
    {
        $transporteur = Transporteur::all();
        $action = "create";
        return view('vehicules.create', compact('transporteur', 'action'));
    }

    /**
     * Store a newly created Vehicule in storage.
     *
     * @param CreateVehiculeRequest $request
     *
     * @return Response
     */
    public function store(CreateVehiculeRequest $request)
    {
        $input = $request->all();

        $vehicule = $this->vehiculeRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/vehicules.singular')]));

        return redirect(route('vehicules.index'));
    }

    public function count_car_in_calendar(TrucknotscoringDataTable $dataTable, Request $request)
    {
        $id_planning = $request->id_planning  ?? DB::table('import_calendar')->latest('id')->value('id');

        $importTrucks = ImportExcel::where('import_calendar_id', $id_planning)
        ->distinct()
        ->pluck('camion')
        ->map(function ($camion) {
            return strpos($camion, ' - ') !== false ? explode(' - ', $camion)[0] : $camion;
        })
        ->unique() // Supprime les doublons après transformation
        ->toArray();

        $missingTrucks = $importTrucks;
        $missingTrucks = array_map(fn($immatriculation) => ['immatriculation' => $immatriculation], $missingTrucks);
            
        return $dataTable->with(['data' => $missingTrucks])->render('vehicules.list_vehicule');
    }

    public function count_driver_not_has_scoring(TrucknotscoringDataTable $dataTable, Request $request)
    {
        $id_planning = $request->id_planning  ?? DB::table('import_calendar')->latest('id')->value('id');
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
        
        $missingTrucks = array_map(fn($immatriculation) => ['immatriculation' => $immatriculation], $result);
        
            
        return $dataTable->with(['data' => $missingTrucks])->render('vehicules.list_vehicule');
    }

    /**
     * Display the specified Vehicule.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $vehicule = $this->vehiculeRepository->find($id);
        $vehicule_update = VehiculeUpdate::with('transporteur')->where('vehicule_id',$id)->get();
        if (empty($vehicule)) {
            Flash::error(__('messages.not_found', ['model' => __('models/vehicules.singular')]));

            return redirect(route('vehicules.index'));
        }

        return view('vehicules.show',compact('vehicule', 'vehicule_update'));
    }

    /**
     * Show the form for editing the specified Vehicule.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $vehicule = $this->vehiculeRepository->find($id);
        $transporteur = Transporteur::find($vehicule->id_transporteur);
        $action = "edit";
        if (empty($vehicule)) {
            Flash::error(__('messages.not_found', ['model' => __('models/vehicules.singular')]));

            return redirect(route('vehicules.index'));
        }

        return view('vehicules.edit' , compact('vehicule', 'transporteur','action'));
    }

    /**
     * Update the specified Vehicule in storage.
     *
     * @param int $id
     * @param UpdateVehiculeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVehiculeRequest $request)
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if (empty($vehicule)) {
            Flash::error(__('messages.not_found', ['model' => __('models/vehicules.singular')]));

            return redirect(route('vehicules.index'));
        }

        $vehicule = $this->vehiculeRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/vehicules.singular')]));

        return redirect(route('vehicules.index'));
    }

    /**
     * Remove the specified Vehicule from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if (empty($vehicule)) {
            Flash::error(__('messages.not_found', ['model' => __('models/vehicules.singular')]));

            return redirect(route('vehicules.index'));
        }

        $this->vehiculeRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/vehicules.singular')]));

        return redirect(route('vehicules.index'));
    }
}
