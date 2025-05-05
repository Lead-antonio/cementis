<?php

namespace App\Http\Controllers;

use App\DataTables\IncidentVehiculeDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateIncidentVehiculeRequest;
use App\Http\Requests\UpdateIncidentVehiculeRequest;
use App\Repositories\IncidentVehiculeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class IncidentVehiculeController extends AppBaseController
{
    /** @var IncidentVehiculeRepository $incidentVehiculeRepository*/
    private $incidentVehiculeRepository;

    public function __construct(IncidentVehiculeRepository $incidentVehiculeRepo)
    {
        $this->incidentVehiculeRepository = $incidentVehiculeRepo;
    }

    /**
     * Display a listing of the IncidentVehicule.
     *
     * @param IncidentVehiculeDataTable $incidentVehiculeDataTable
     *
     * @return Response
     */
    public function index(IncidentVehiculeDataTable $incidentVehiculeDataTable)
    {
        return $incidentVehiculeDataTable->render('incident_vehicules.index');
    }

    /**
     * Show the form for creating a new IncidentVehicule.
     *
     * @return Response
     */
    public function create()
    {
        return view('incident_vehicules.create');
    }

    /**
     * Store a newly created IncidentVehicule in storage.
     *
     * @param CreateIncidentVehiculeRequest $request
     *
     * @return Response
     */
    public function store(CreateIncidentVehiculeRequest $request)
    {
        $input = $request->all();

        $incidentVehicule = $this->incidentVehiculeRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/incidentVehicules.singular')]));

        return redirect(route('incidentVehicules.index'));
    }

    /**
     * Display the specified IncidentVehicule.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $incidentVehicule = $this->incidentVehiculeRepository->find($id);

        if (empty($incidentVehicule)) {
            Flash::error(__('messages.not_found', ['model' => __('models/incidentVehicules.singular')]));

            return redirect(route('incidentVehicules.index'));
        }

        return view('incident_vehicules.show')->with('incidentVehicule', $incidentVehicule);
    }

    /**
     * Show the form for editing the specified IncidentVehicule.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $incidentVehicule = $this->incidentVehiculeRepository->find($id);

        if (empty($incidentVehicule)) {
            Flash::error(__('messages.not_found', ['model' => __('models/incidentVehicules.singular')]));

            return redirect(route('incidentVehicules.index'));
        }

        return view('incident_vehicules.edit')->with('incidentVehicule', $incidentVehicule);
    }

    /**
     * Update the specified IncidentVehicule in storage.
     *
     * @param int $id
     * @param UpdateIncidentVehiculeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateIncidentVehiculeRequest $request)
    {
        $incidentVehicule = $this->incidentVehiculeRepository->find($id);

        if (empty($incidentVehicule)) {
            Flash::error(__('messages.not_found', ['model' => __('models/incidentVehicules.singular')]));

            return redirect(route('incidentVehicules.index'));
        }

        $incidentVehicule = $this->incidentVehiculeRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/incidentVehicules.singular')]));

        return redirect(route('incidentVehicules.index'));
    }

    /**
     * Remove the specified IncidentVehicule from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $incidentVehicule = $this->incidentVehiculeRepository->find($id);

        if (empty($incidentVehicule)) {
            Flash::error(__('messages.not_found', ['model' => __('models/incidentVehicules.singular')]));

            return redirect(route('incidentVehicules.index'));
        }

        $this->incidentVehiculeRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/incidentVehicules.singular')]));

        return redirect(route('incidentVehicules.index'));
    }
}
