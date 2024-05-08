<?php

namespace App\Http\Controllers;

use App\DataTables\InfractionDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateInfractionRequest;
use App\Http\Requests\UpdateInfractionRequest;
use App\Repositories\InfractionRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class InfractionController extends AppBaseController
{
    /** @var InfractionRepository $infractionRepository*/
    private $infractionRepository;

    public function __construct(InfractionRepository $infractionRepo)
    {
        $this->infractionRepository = $infractionRepo;
    }

    /**
     * Display a listing of the Infraction.
     *
     * @param InfractionDataTable $infractionDataTable
     *
     * @return Response
     */
    public function index(InfractionDataTable $infractionDataTable)
    {
        return $infractionDataTable->render('infractions.index');
    }

    /**
     * Show the form for creating a new Infraction.
     *
     * @return Response
     */
    public function create()
    {
        return view('infractions.create');
    }

    /**
     * Store a newly created Infraction in storage.
     *
     * @param CreateInfractionRequest $request
     *
     * @return Response
     */
    public function store(CreateInfractionRequest $request)
    {
        $input = $request->all();

        $infraction = $this->infractionRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/infractions.singular')]));

        return redirect(route('infractions.index'));
    }

    /**
     * Display the specified Infraction.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $infraction = $this->infractionRepository->find($id);

        if (empty($infraction)) {
            Flash::error(__('messages.not_found', ['model' => __('models/infractions.singular')]));

            return redirect(route('infractions.index'));
        }

        return view('infractions.show')->with('infraction', $infraction);
    }

    /**
     * Show the form for editing the specified Infraction.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $infraction = $this->infractionRepository->find($id);

        if (empty($infraction)) {
            Flash::error(__('messages.not_found', ['model' => __('models/infractions.singular')]));

            return redirect(route('infractions.index'));
        }

        return view('infractions.edit')->with('infraction', $infraction);
    }

    /**
     * Update the specified Infraction in storage.
     *
     * @param int $id
     * @param UpdateInfractionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInfractionRequest $request)
    {
        $infraction = $this->infractionRepository->find($id);

        if (empty($infraction)) {
            Flash::error(__('messages.not_found', ['model' => __('models/infractions.singular')]));

            return redirect(route('infractions.index'));
        }

        $infraction = $this->infractionRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/infractions.singular')]));

        return redirect(route('infractions.index'));
    }

    /**
     * Remove the specified Infraction from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $infraction = $this->infractionRepository->find($id);

        if (empty($infraction)) {
            Flash::error(__('messages.not_found', ['model' => __('models/infractions.singular')]));

            return redirect(route('infractions.index'));
        }

        $this->infractionRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/infractions.singular')]));

        return redirect(route('infractions.index'));
    }
}
