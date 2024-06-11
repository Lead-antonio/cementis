<?php

namespace App\Http\Controllers;

use App\DataTables\ScoringDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateScoringRequest;
use App\Http\Requests\UpdateScoringRequest;
use App\Repositories\ScoringRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ScoringController extends AppBaseController
{
    /** @var ScoringRepository $scoringRepository*/
    private $scoringRepository;

    public function __construct(ScoringRepository $scoringRepo)
    {
        $this->scoringRepository = $scoringRepo;
    }

    /**
     * Display a listing of the Scoring.
     *
     * @param ScoringDataTable $scoringDataTable
     *
     * @return Response
     */
    public function index(ScoringDataTable $scoringDataTable)
    {
        return $scoringDataTable->render('scorings.index');
    }

    /**
     * Show the form for creating a new Scoring.
     *
     * @return Response
     */
    public function create()
    {
        return view('scorings.create');
    }

    /**
     * Store a newly created Scoring in storage.
     *
     * @param CreateScoringRequest $request
     *
     * @return Response
     */
    public function store(CreateScoringRequest $request)
    {
        $input = $request->all();

        $scoring = $this->scoringRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/scorings.singular')]));

        return redirect(route('scorings.index'));
    }

    /**
     * Display the specified Scoring.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $scoring = $this->scoringRepository->find($id);

        if (empty($scoring)) {
            Flash::error(__('messages.not_found', ['model' => __('models/scorings.singular')]));

            return redirect(route('scorings.index'));
        }

        return view('scorings.show')->with('scoring', $scoring);
    }

    /**
     * Show the form for editing the specified Scoring.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $scoring = $this->scoringRepository->find($id);

        if (empty($scoring)) {
            Flash::error(__('messages.not_found', ['model' => __('models/scorings.singular')]));

            return redirect(route('scorings.index'));
        }

        return view('scorings.edit')->with('scoring', $scoring);
    }

    /**
     * Update the specified Scoring in storage.
     *
     * @param int $id
     * @param UpdateScoringRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateScoringRequest $request)
    {
        $scoring = $this->scoringRepository->find($id);

        if (empty($scoring)) {
            Flash::error(__('messages.not_found', ['model' => __('models/scorings.singular')]));

            return redirect(route('scorings.index'));
        }

        $scoring = $this->scoringRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/scorings.singular')]));

        return redirect(route('scorings.index'));
    }

    /**
     * Remove the specified Scoring from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $scoring = $this->scoringRepository->find($id);

        if (empty($scoring)) {
            Flash::error(__('messages.not_found', ['model' => __('models/scorings.singular')]));

            return redirect(route('scorings.index'));
        }

        $this->scoringRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/scorings.singular')]));

        return redirect(route('scorings.index'));
    }
}
