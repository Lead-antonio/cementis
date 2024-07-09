<?php

namespace App\Http\Controllers;

use App\DataTables\ImportInstallationErrorDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateImportInstallationErrorRequest;
use App\Http\Requests\UpdateImportInstallationErrorRequest;
use App\Repositories\ImportInstallationErrorRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ImportInstallationErrorController extends AppBaseController
{
    /** @var ImportInstallationErrorRepository $importInstallationErrorRepository*/
    private $importInstallationErrorRepository;

    public function __construct(ImportInstallationErrorRepository $importInstallationErrorRepo)
    {
        $this->importInstallationErrorRepository = $importInstallationErrorRepo;
    }

    /**
     * Display a listing of the ImportInstallationError.
     *
     * @param ImportInstallationErrorDataTable $importInstallationErrorDataTable
     *
     * @return Response
     */
    public function index(ImportInstallationErrorDataTable $importInstallationErrorDataTable)
    {
        return $importInstallationErrorDataTable->render('import_installation_errors.index');
    }

    /**
     * Show the form for creating a new ImportInstallationError.
     *
     * @return Response
     */
    public function create()
    {
        return view('import_installation_errors.create');
    }

    /**
     * Store a newly created ImportInstallationError in storage.
     *
     * @param CreateImportInstallationErrorRequest $request
     *
     * @return Response
     */
    public function store(CreateImportInstallationErrorRequest $request)
    {
        $input = $request->all();

        $importInstallationError = $this->importInstallationErrorRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/importInstallationErrors.singular')]));

        return redirect(route('importInstallationErrors.index'));
    }

    /**
     * Display the specified ImportInstallationError.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $importInstallationError = $this->importInstallationErrorRepository->find($id);

        if (empty($importInstallationError)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importInstallationErrors.singular')]));

            return redirect(route('importInstallationErrors.index'));
        }

        return view('import_installation_errors.show')->with('importInstallationError', $importInstallationError);
    }

    /**
     * Show the form for editing the specified ImportInstallationError.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $importInstallationError = $this->importInstallationErrorRepository->find($id);

        if (empty($importInstallationError)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importInstallationErrors.singular')]));

            return redirect(route('importInstallationErrors.index'));
        }

        return view('import_installation_errors.edit')->with('importInstallationError', $importInstallationError);
    }

    /**
     * Update the specified ImportInstallationError in storage.
     *
     * @param int $id
     * @param UpdateImportInstallationErrorRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImportInstallationErrorRequest $request)
    {
        $importInstallationError = $this->importInstallationErrorRepository->find($id);

        if (empty($importInstallationError)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importInstallationErrors.singular')]));

            return redirect(route('importInstallationErrors.index'));
        }

        $importInstallationError = $this->importInstallationErrorRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/importInstallationErrors.singular')]));

        return redirect(route('importInstallationErrors.index'));
    }

    /**
     * Remove the specified ImportInstallationError from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $importInstallationError = $this->importInstallationErrorRepository->find($id);

        if (empty($importInstallationError)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importInstallationErrors.singular')]));

            return redirect(route('importInstallationErrors.index'));
        }

        $this->importInstallationErrorRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/importInstallationErrors.singular')]));

        return redirect(route('importInstallationErrors.index'));
    }
}
