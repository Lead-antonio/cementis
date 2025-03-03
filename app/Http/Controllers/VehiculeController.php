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
use App\Models\Vehicule;
use App\Models\Chauffeur;
use App\Models\ImportExcel;
use App\Models\Scoring;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use App\Models\VehiculeUpdate;
use Response;

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
    public function index(VehiculeDataTable $vehiculeDataTable)
    {
        return $vehiculeDataTable->render('vehicules.index');
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

    public function count_driver_not_has_scoring(TrucknotscoringDataTable $dataTable)
    {
        $id_planning = DB::table('import_calendar')->latest('id')->value('id');

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

        $missingTrucks = array_map(fn($immatriculation) => ['immatriculation' => $immatriculation], $missingTrucks);
        // dd($missingTrucks);
            
        return $dataTable->with(['data' => $missingTrucks])->render('vehicules.list_vehicule');

        // return view('vehicules.list_vehicule')->with('vehicule', $missingTrucks);
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
