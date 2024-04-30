<?php

namespace App\Http\Controllers;

use App\DataTables\GroupeEventDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateGroupeEventRequest;
use App\Http\Requests\UpdateGroupeEventRequest;
use App\Repositories\GroupeEventRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class GroupeEventController extends AppBaseController
{
    /** @var GroupeEventRepository $groupeEventRepository*/
    private $groupeEventRepository;

    public function __construct(GroupeEventRepository $groupeEventRepo)
    {
        $this->groupeEventRepository = $groupeEventRepo;
    }

    /**
     * Display a listing of the GroupeEvent.
     *
     * @param GroupeEventDataTable $groupeEventDataTable
     *
     * @return Response
     */
    public function index(GroupeEventDataTable $groupeEventDataTable)
    {
        return $groupeEventDataTable->render('groupe_events.index');
    }

    /**
     * Show the form for creating a new GroupeEvent.
     *
     * @return Response
     */
    public function create()
    {
        return view('groupe_events.create');
    }

    /**
     * Store a newly created GroupeEvent in storage.
     *
     * @param CreateGroupeEventRequest $request
     *
     * @return Response
     */
    public function store(CreateGroupeEventRequest $request)
    {
        $input = $request->all();

        $groupeEvent = $this->groupeEventRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/groupeEvents.singular')]));

        return redirect(route('groupeEvents.index'));
    }

    /**
     * Display the specified GroupeEvent.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $groupeEvent = $this->groupeEventRepository->find($id);

        if (empty($groupeEvent)) {
            Flash::error(__('messages.not_found', ['model' => __('models/groupeEvents.singular')]));

            return redirect(route('groupeEvents.index'));
        }

        return view('groupe_events.show')->with('groupeEvent', $groupeEvent);
    }

    /**
     * Show the form for editing the specified GroupeEvent.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $groupeEvent = $this->groupeEventRepository->find($id);

        if (empty($groupeEvent)) {
            Flash::error(__('messages.not_found', ['model' => __('models/groupeEvents.singular')]));

            return redirect(route('groupeEvents.index'));
        }

        return view('groupe_events.edit')->with('groupeEvent', $groupeEvent);
    }

    /**
     * Update the specified GroupeEvent in storage.
     *
     * @param int $id
     * @param UpdateGroupeEventRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGroupeEventRequest $request)
    {
        $groupeEvent = $this->groupeEventRepository->find($id);

        if (empty($groupeEvent)) {
            Flash::error(__('messages.not_found', ['model' => __('models/groupeEvents.singular')]));

            return redirect(route('groupeEvents.index'));
        }

        $groupeEvent = $this->groupeEventRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/groupeEvents.singular')]));

        return redirect(route('groupeEvents.index'));
    }

    /**
     * Remove the specified GroupeEvent from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $groupeEvent = $this->groupeEventRepository->find($id);

        if (empty($groupeEvent)) {
            Flash::error(__('messages.not_found', ['model' => __('models/groupeEvents.singular')]));

            return redirect(route('groupeEvents.index'));
        }

        $this->groupeEventRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/groupeEvents.singular')]));

        return redirect(route('groupeEvents.index'));
    }
}
