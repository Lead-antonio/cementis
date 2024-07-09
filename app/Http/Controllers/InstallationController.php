<?php

namespace App\Http\Controllers;

use App\DataTables\InstallationDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateInstallationRequest;
use App\Http\Requests\UpdateInstallationRequest;
use App\Repositories\InstallationRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Vehicule;
use Response;

class InstallationController extends AppBaseController
{
    /** @var InstallationRepository $installationRepository*/
    private $installationRepository;

    public function __construct(InstallationRepository $installationRepo)
    {
        $this->installationRepository = $installationRepo;
    }

    /**
     * Display a listing of the Installation.
     *
     * @param InstallationDataTable $installationDataTable
     *
     * @return Response
     */
    public function index(InstallationDataTable $installationDataTable)
    {
        // $resp = Vehicule::all();
        // dd($resp);
        return $installationDataTable->render('installations.index');
    }

    /**
     * Show the form for creating a new Installation.
     *
     * @return Response
     */
    public function create()
    {
        return view('installations.create');
    }

    /**
     * Store a newly created Installation in storage.
     *
     * @param CreateInstallationRequest $request
     *
     * @return Response
     */
    public function store(CreateInstallationRequest $request)
    {
        $input = $request->all();

        $installation = $this->installationRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/installations.singular')]));

        return redirect(route('installations.index'));
    }

    /**
     * Display the specified Installation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $installation = $this->installationRepository->find($id);

        if (empty($installation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/installations.singular')]));

            return redirect(route('installations.index'));
        }

        return view('installations.show')->with('installation', $installation);
    }

    /**
     * Show the form for editing the specified Installation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $installation = $this->installationRepository->find($id);

        if (empty($installation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/installations.singular')]));

            return redirect(route('installations.index'));
        }

        return view('installations.edit')->with('installation', $installation);
    }

    /**
     * Update the specified Installation in storage.
     *
     * @param int $id
     * @param UpdateInstallationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInstallationRequest $request)
    {
        $installation = $this->installationRepository->find($id);

        if (empty($installation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/installations.singular')]));

            return redirect(route('installations.index'));
        }

        $installation = $this->installationRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/installations.singular')]));

        return redirect(route('installations.index'));
    }

    /**
     * Remove the specified Installation from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $installation = $this->installationRepository->find($id);

        if (empty($installation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/installations.singular')]));

            return redirect(route('installations.index'));
        }

        $this->installationRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/installations.singular')]));

        return redirect(route('installations.index'));
    }
}
