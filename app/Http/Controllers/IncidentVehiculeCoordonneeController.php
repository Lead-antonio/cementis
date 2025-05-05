<?php

namespace App\Http\Controllers;

use App\DataTables\IncidentVehiculeCoordonneeDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateIncidentVehiculeCoordonneeRequest;
use App\Http\Requests\UpdateIncidentVehiculeCoordonneeRequest;
use App\Repositories\IncidentVehiculeCoordonneeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class IncidentVehiculeCoordonneeController extends AppBaseController
{
    /** @var IncidentVehiculeCoordonneeRepository $incidentVehiculeCoordonneeRepository*/
    private $incidentVehiculeCoordonneeRepository;

    public function __construct(IncidentVehiculeCoordonneeRepository $incidentVehiculeCoordonneeRepo)
    {
        $this->incidentVehiculeCoordonneeRepository = $incidentVehiculeCoordonneeRepo;
    }

    /**
     * Display a listing of the IncidentVehiculeCoordonnee.
     *
     * @param IncidentVehiculeCoordonneeDataTable $incidentVehiculeCoordonneeDataTable
     *
     * @return Response
     */
    public function index(IncidentVehiculeCoordonneeDataTable $incidentVehiculeCoordonneeDataTable, $id = null)
    {

        if ($id !== null) {
            return $this->detail_liste_coordonnee($id, $incidentVehiculeCoordonneeDataTable);
        }

    
        return $incidentVehiculeCoordonneeDataTable->render('incident_vehicule_coordonnees.index');
    }

    /**
     * Show the form for creating a new IncidentVehiculeCoordonnee.
     *
     * @return Response
     */
    public function create()
    {
        return view('incident_vehicule_coordonnees.create');
    }

    /**
     * Store a newly created IncidentVehiculeCoordonnee in storage.
     *
     * @param CreateIncidentVehiculeCoordonneeRequest $request
     *
     * @return Response
     */
    public function store(CreateIncidentVehiculeCoordonneeRequest $request)
    {
        $input = $request->all();

        $incidentVehiculeCoordonnee = $this->incidentVehiculeCoordonneeRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/incidentVehiculeCoordonnees.singular')]));

        return redirect(route('incidentVehiculeCoordonnees.index'));
    }

    /**
     * Display the specified IncidentVehiculeCoordonnee.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $incidentVehiculeCoordonnee = $this->incidentVehiculeCoordonneeRepository->find($id);

        if (empty($incidentVehiculeCoordonnee)) {
            Flash::error(__('messages.not_found', ['model' => __('models/incidentVehiculeCoordonnees.singular')]));

            return redirect(route('incidentVehiculeCoordonnees.index'));
        }

        return view('incident_vehicule_coordonnees.show')->with('incidentVehiculeCoordonnee', $incidentVehiculeCoordonnee);
    }

    /**
     * Show the form for editing the specified IncidentVehiculeCoordonnee.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $incidentVehiculeCoordonnee = $this->incidentVehiculeCoordonneeRepository->find($id);

        if (empty($incidentVehiculeCoordonnee)) {
            Flash::error(__('messages.not_found', ['model' => __('models/incidentVehiculeCoordonnees.singular')]));

            return redirect(route('incidentVehiculeCoordonnees.index'));
        }

        return view('incident_vehicule_coordonnees.edit')->with('incidentVehiculeCoordonnee', $incidentVehiculeCoordonnee);
    }

    /**
     * Update the specified IncidentVehiculeCoordonnee in storage.
     *
     * @param int $id
     * @param UpdateIncidentVehiculeCoordonneeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateIncidentVehiculeCoordonneeRequest $request)
    {
        $incidentVehiculeCoordonnee = $this->incidentVehiculeCoordonneeRepository->find($id);

        if (empty($incidentVehiculeCoordonnee)) {
            Flash::error(__('messages.not_found', ['model' => __('models/incidentVehiculeCoordonnees.singular')]));

            return redirect(route('incidentVehiculeCoordonnees.index'));
        }

        $incidentVehiculeCoordonnee = $this->incidentVehiculeCoordonneeRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/incidentVehiculeCoordonnees.singular')]));

        return redirect(route('incidentVehiculeCoordonnees.index'));
    }

    /**
     * Remove the specified IncidentVehiculeCoordonnee from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $incidentVehiculeCoordonnee = $this->incidentVehiculeCoordonneeRepository->find($id);

        if (empty($incidentVehiculeCoordonnee)) {
            Flash::error(__('messages.not_found', ['model' => __('models/incidentVehiculeCoordonnees.singular')]));

            return redirect(route('incidentVehiculeCoordonnees.index'));
        }

        $this->incidentVehiculeCoordonneeRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/incidentVehiculeCoordonnees.singular')]));

        return redirect(route('incidentVehiculeCoordonnees.index'));
    }


    
    /**
     * Display a listing of the ImportExcel filtered by id.
     *
     * @param int $id
     * @param IncidentVehiculeCoordonneeDataTable $IncidentVehiculeCoordonneeDataTable
     * @return Response
     */
    public function detail_liste_coordonnee($id, IncidentVehiculeCoordonneeDataTable $IncidentVehiculeCoordonneeDataTable)
    {
        return $IncidentVehiculeCoordonneeDataTable->with('id', $id)->render('incident_vehicule_coordonnees.index');
    }

}
