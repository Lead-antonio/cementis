<?php

namespace App\Http\Controllers;

use App\DataTables\ImportModelDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateImportModelRequest;
use App\Http\Requests\UpdateImportModelRequest;
use App\Repositories\ImportModelRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;

class ImportModelController extends AppBaseController
{
    /** @var ImportModelRepository $importModelRepository*/
    private $importModelRepository;

    public function __construct(ImportModelRepository $importModelRepo)
    {
        $this->importModelRepository = $importModelRepo;
    }

    /**
     * Display a listing of the ImportModel.
     *
     * @param ImportModelDataTable $importModelDataTable
     *
     * @return Response
     */
    public function index(ImportModelDataTable $importModelDataTable)
    {
        return $importModelDataTable->render('import_models.index');
    }

    /**
     * Show the form for creating a new ImportModel.
     *
     * @return Response
     */
    public function create()
    {
        return view('import_models.create');
    }

    /**
     * Store a newly created ImportModel in storage.
     *
     * @param CreateImportModelRequest $request
     *
     * @return Response
     */
    public function store(CreateImportModelRequest $request)
    {
        $input = $request->all();

        $importModel = $this->importModelRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/importModels.singular')]));

        return redirect(route('importModels.index'));
    }

    /**
     * Display the specified ImportModel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $importModel = $this->importModelRepository->find($id);

        if (empty($importModel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importModels.singular')]));

            return redirect(route('importModels.index'));
        }

        return view('import_models.show')->with('importModel', $importModel);
    }

    /**
     * Show the form for editing the specified ImportModel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $importModel = $this->importModelRepository->find($id);

        if (empty($importModel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importModels.singular')]));

            return redirect(route('importModels.index'));
        }

        // Convertir le tableau JSON en une chaîne JSON pour préremplir le champ
        $importModel->association = json_encode($importModel->association, JSON_PRETTY_PRINT);

        return view('import_models.edit')->with('importModel', $importModel);
    }



    /**
     * Update the specified ImportModel in storage.
     *
     * @param int $id
     * @param UpdateImportModelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImportModelRequest $request)
    {
        $importModel = $this->importModelRepository->find($id);

        if (empty($importModel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importModels.singular')]));

            return redirect(route('importModels.index'));
        }

        $input = $request->all();

        // Convertir la chaîne JSON du formulaire en tableau
        $input['association'] = json_decode($input['association'], true);

        $this->importModelRepository->update($input, $id);

        Flash::success(__('messages.updated', ['model' => __('models/importModels.singular')]));

        return redirect(route('importModels.index'));
    }

    /**
     * Remove the specified ImportModel from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $importModel = $this->importModelRepository->find($id);

        if (empty($importModel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importModels.singular')]));

            return redirect(route('importModels.index'));
        }

        $this->importModelRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/importModels.singular')]));

        return redirect(route('importModels.index'));
    }
}
