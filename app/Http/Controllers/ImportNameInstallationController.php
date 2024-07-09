<?php

namespace App\Http\Controllers;

use App\DataTables\ImportNameInstallationDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateImportNameInstallationRequest;
use App\Http\Requests\UpdateImportNameInstallationRequest;
use App\Repositories\ImportNameInstallationRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ImportNameInstallationController extends AppBaseController
{
    /** @var ImportNameInstallationRepository $importNameInstallationRepository*/
    private $importNameInstallationRepository;

    public function __construct(ImportNameInstallationRepository $importNameInstallationRepo)
    {
        $this->importNameInstallationRepository = $importNameInstallationRepo;
    }

    /**
     * Display a listing of the ImportNameInstallation.
     *
     * @param ImportNameInstallationDataTable $importNameInstallationDataTable
     *
     * @return Response
     */
    public function index(ImportNameInstallationDataTable $importNameInstallationDataTable)
    {
        return $importNameInstallationDataTable->render('import_name_installations.index');
    }

    /**
     * Show the form for creating a new ImportNameInstallation.
     *
     * @return Response
     */
    public function create()
    {
        return view('import_name_installations.create');
    }

    /**
     * Store a newly created ImportNameInstallation in storage.
     *
     * @param CreateImportNameInstallationRequest $request
     *
     * @return Response
     */
    public function store(CreateImportNameInstallationRequest $request)
    {
        $input = $request->all();

        $importNameInstallation = $this->importNameInstallationRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/importNameInstallations.singular')]));

        return redirect(route('importNameInstallations.index'));
    }

    /**
     * Display the specified ImportNameInstallation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $importNameInstallation = $this->importNameInstallationRepository->find($id);

        if (empty($importNameInstallation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importNameInstallations.singular')]));

            return redirect(route('importNameInstallations.index'));
        }

        return view('import_name_installations.show')->with('importNameInstallation', $importNameInstallation);
    }

    /**
     * Show the form for editing the specified ImportNameInstallation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $importNameInstallation = $this->importNameInstallationRepository->find($id);

        if (empty($importNameInstallation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importNameInstallations.singular')]));

            return redirect(route('importNameInstallations.index'));
        }

        return view('import_name_installations.edit')->with('importNameInstallation', $importNameInstallation);
    }

    /**
     * Update the specified ImportNameInstallation in storage.
     *
     * @param int $id
     * @param UpdateImportNameInstallationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImportNameInstallationRequest $request)
    {
        $importNameInstallation = $this->importNameInstallationRepository->find($id);

        if (empty($importNameInstallation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importNameInstallations.singular')]));

            return redirect(route('importNameInstallations.index'));
        }

        $importNameInstallation = $this->importNameInstallationRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/importNameInstallations.singular')]));

        return redirect(route('importNameInstallations.index'));
    }

    /**
     * Remove the specified ImportNameInstallation from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $importNameInstallation = $this->importNameInstallationRepository->find($id);

        if (empty($importNameInstallation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importNameInstallations.singular')]));

            return redirect(route('importNameInstallations.index'));
        }

        $this->importNameInstallationRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/importNameInstallations.singular')]));

        return redirect(route('importNameInstallations.index'));
    }
}
