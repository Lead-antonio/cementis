<?php

namespace App\Http\Controllers;

use App\DataTables\ImportcalendarDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateImportcalendarRequest;
use App\Http\Requests\UpdateImportcalendarRequest;
use App\Repositories\ImportcalendarRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ImportcalendarController extends AppBaseController
{
    /** @var ImportcalendarRepository $importcalendarRepository*/
    private $importcalendarRepository;

    public function __construct(ImportcalendarRepository $importcalendarRepo)
    {
        $this->importcalendarRepository = $importcalendarRepo;
    }

    /**
     * Display a listing of the Importcalendar.
     *
     * @param ImportcalendarDataTable $importcalendarDataTable
     *
     * @return Response
     */
    public function index(ImportcalendarDataTable $importcalendarDataTable)
    {
        return $importcalendarDataTable->render('importcalendars.index');
    }

    /**
     * Show the form for creating a new Importcalendar.
     *
     * @return Response
     */
    public function create()
    {
        return view('importcalendars.create');
    }

    /**
     * Store a newly created Importcalendar in storage.
     *
     * @param CreateImportcalendarRequest $request
     *
     * @return Response
     */
    public function store(CreateImportcalendarRequest $request)
    {
        $input = $request->all();

        $importcalendar = $this->importcalendarRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/importcalendars.singular')]));

        return redirect(route('importcalendars.index'));
    }

    /**
     * Display the specified Importcalendar.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $importcalendar = $this->importcalendarRepository->find($id);

        if (empty($importcalendar)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importcalendars.singular')]));

            return redirect(route('importcalendars.index'));
        }

        return view('importcalendars.show')->with('importcalendar', $importcalendar);
    }

    /**
     * Show the form for editing the specified Importcalendar.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $importcalendar = $this->importcalendarRepository->find($id);

        if (empty($importcalendar)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importcalendars.singular')]));

            return redirect(route('importcalendars.index'));
        }

        return view('importcalendars.edit')->with('importcalendar', $importcalendar);
    }

    /**
     * Update the specified Importcalendar in storage.
     *
     * @param int $id
     * @param UpdateImportcalendarRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImportcalendarRequest $request)
    {
        $importcalendar = $this->importcalendarRepository->find($id);

        if (empty($importcalendar)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importcalendars.singular')]));

            return redirect(route('importcalendars.index'));
        }

        $importcalendar = $this->importcalendarRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/importcalendars.singular')]));

        return redirect(route('importcalendars.index'));
    }

    /**
     * Remove the specified Importcalendar from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $importcalendar = $this->importcalendarRepository->find($id);

        if (empty($importcalendar)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importcalendars.singular')]));

            return redirect(route('importcalendars.index'));
        }

        $this->importcalendarRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/importcalendars.singular')]));

        return redirect(route('importcalendars.index'));
    }
}
