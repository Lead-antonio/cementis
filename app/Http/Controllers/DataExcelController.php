<?php

namespace App\Http\Controllers;

use App\DataTables\DataExcelDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDataExcelRequest;
use App\Http\Requests\UpdateDataExcelRequest;
use App\Repositories\DataExcelRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class DataExcelController extends AppBaseController
{
    /** @var DataExcelRepository $dataExcelRepository*/
    private $dataExcelRepository;

    public function __construct(DataExcelRepository $dataExcelRepo)
    {
        $this->dataExcelRepository = $dataExcelRepo;
    }

    /**
     * Display a listing of the DataExcel.
     *
     * @param DataExcelDataTable $dataExcelDataTable
     *
     * @return Response
     */
    public function index(DataExcelDataTable $dataExcelDataTable)
    {
        return $dataExcelDataTable->render('data_excels.index');
    }

    /**
     * Show the form for creating a new DataExcel.
     *
     * @return Response
     */
    public function create()
    {
        return view('data_excels.create');
    }

    /**
     * Store a newly created DataExcel in storage.
     *
     * @param CreateDataExcelRequest $request
     *
     * @return Response
     */
    public function store(CreateDataExcelRequest $request)
    {
        $input = $request->all();

        $dataExcel = $this->dataExcelRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/dataExcels.singular')]));

        return redirect(route('dataExcels.index'));
    }

    /**
     * Display the specified DataExcel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $dataExcel = $this->dataExcelRepository->find($id);

        if (empty($dataExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/dataExcels.singular')]));

            return redirect(route('dataExcels.index'));
        }

        return view('data_excels.show')->with('dataExcel', $dataExcel);
    }

    /**
     * Show the form for editing the specified DataExcel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $dataExcel = $this->dataExcelRepository->find($id);

        if (empty($dataExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/dataExcels.singular')]));

            return redirect(route('dataExcels.index'));
        }

        return view('data_excels.edit')->with('dataExcel', $dataExcel);
    }

    /**
     * Update the specified DataExcel in storage.
     *
     * @param int $id
     * @param UpdateDataExcelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDataExcelRequest $request)
    {
        $dataExcel = $this->dataExcelRepository->find($id);

        if (empty($dataExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/dataExcels.singular')]));

            return redirect(route('dataExcels.index'));
        }

        $dataExcel = $this->dataExcelRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/dataExcels.singular')]));

        return redirect(route('dataExcels.index'));
    }

    /**
     * Remove the specified DataExcel from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $dataExcel = $this->dataExcelRepository->find($id);

        if (empty($dataExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/dataExcels.singular')]));

            return redirect(route('dataExcels.index'));
        }

        $this->dataExcelRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/dataExcels.singular')]));

        return redirect(route('dataExcels.index'));
    }
}
