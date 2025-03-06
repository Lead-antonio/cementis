<?php

namespace App\Http\Controllers;

use App\DataTables\ChauffeurUpdateTypeDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateChauffeurUpdateTypeRequest;
use App\Http\Requests\UpdateChauffeurUpdateTypeRequest;
use App\Repositories\ChauffeurUpdateTypeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ChauffeurUpdateTypeController extends AppBaseController
{
    /** @var ChauffeurUpdateTypeRepository $chauffeurUpdateTypeRepository*/
    private $chauffeurUpdateTypeRepository;

    public function __construct(ChauffeurUpdateTypeRepository $chauffeurUpdateTypeRepo)
    {
        $this->chauffeurUpdateTypeRepository = $chauffeurUpdateTypeRepo;
    }

    /**
     * Display a listing of the ChauffeurUpdateType.
     *
     * @param ChauffeurUpdateTypeDataTable $chauffeurUpdateTypeDataTable
     *
     * @return Response
     */
    public function index(ChauffeurUpdateTypeDataTable $chauffeurUpdateTypeDataTable)
    {
        return $chauffeurUpdateTypeDataTable->render('chauffeur_update_types.index');
    }

    /**
     * Show the form for creating a new ChauffeurUpdateType.
     *
     * @return Response
     */
    public function create()
    {
        return view('chauffeur_update_types.create');
    }

    /**
     * Store a newly created ChauffeurUpdateType in storage.
     *
     * @param CreateChauffeurUpdateTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateChauffeurUpdateTypeRequest $request)
    {
        $input = $request->all();

        $chauffeurUpdateType = $this->chauffeurUpdateTypeRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/chauffeurUpdateTypes.singular')]));

        return redirect(route('chauffeurUpdateTypes.index'));
    }

    /**
     * Display the specified ChauffeurUpdateType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $chauffeurUpdateType = $this->chauffeurUpdateTypeRepository->find($id);

        if (empty($chauffeurUpdateType)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateTypes.singular')]));

            return redirect(route('chauffeurUpdateTypes.index'));
        }

        return view('chauffeur_update_types.show')->with('chauffeurUpdateType', $chauffeurUpdateType);
    }

    /**
     * Show the form for editing the specified ChauffeurUpdateType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $chauffeurUpdateType = $this->chauffeurUpdateTypeRepository->find($id);

        if (empty($chauffeurUpdateType)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateTypes.singular')]));

            return redirect(route('chauffeurUpdateTypes.index'));
        }

        return view('chauffeur_update_types.edit')->with('chauffeurUpdateType', $chauffeurUpdateType);
    }

    /**
     * Update the specified ChauffeurUpdateType in storage.
     *
     * @param int $id
     * @param UpdateChauffeurUpdateTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChauffeurUpdateTypeRequest $request)
    {
        $chauffeurUpdateType = $this->chauffeurUpdateTypeRepository->find($id);

        if (empty($chauffeurUpdateType)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateTypes.singular')]));

            return redirect(route('chauffeurUpdateTypes.index'));
        }

        $chauffeurUpdateType = $this->chauffeurUpdateTypeRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/chauffeurUpdateTypes.singular')]));

        return redirect(route('chauffeurUpdateTypes.index'));
    }

    /**
     * Remove the specified ChauffeurUpdateType from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $chauffeurUpdateType = $this->chauffeurUpdateTypeRepository->find($id);

        if (empty($chauffeurUpdateType)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateTypes.singular')]));

            return redirect(route('chauffeurUpdateTypes.index'));
        }

        $this->chauffeurUpdateTypeRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/chauffeurUpdateTypes.singular')]));

        return redirect(route('chauffeurUpdateTypes.index'));
    }
}
