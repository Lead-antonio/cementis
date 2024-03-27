<?php

namespace App\Http\Controllers;

use App\DataTables\PenaliteDataTable;
use App\Http\Requests;
use App\Models\Penalite;
use App\Http\Requests\CreatePenaliteRequest;
use App\Http\Requests\UpdatePenaliteRequest;
use App\Repositories\PenaliteRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class PenaliteController extends AppBaseController
{
    /** @var PenaliteRepository $penaliteRepository*/
    private $penaliteRepository;

    public function __construct(PenaliteRepository $penaliteRepo)
    {
        $this->penaliteRepository = $penaliteRepo;
    }

    /**
     * Display a listing of the Penalite.
     *
     * @param PenaliteDataTable $penaliteDataTable
     *
     * @return Response
     */
    public function index(PenaliteDataTable $penaliteDataTable)
    {
       // dd($this->checkInfraction("Temps de repos hebdomadaire"));
        return $penaliteDataTable->render('penalites.index');
        
    }

    public function checkInfraction($event)
    {
        //modèle Penalite pour vérifier l'infraction et obtenir le point de pénalité correspondant
        $pointPenalite = Penalite::where('event', $event)->value('point_penalite');
        
        // Retourner le point de pénalité ou 0 si aucun enregistrement n'est trouvé
        return $pointPenalite ?? 0;
    }

    /**
     * Show the form for creating a new Penalite.
     *
     * @return Response
     */
    public function create()
    {
        return view('penalites.create');
    }

    /**
     * Store a newly created Penalite in storage.
     *
     * @param CreatePenaliteRequest $request
     *
     * @return Response
     */
    public function store(CreatePenaliteRequest $request)
    {
        $input = $request->all();

        $penalite = $this->penaliteRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/penalites.singular')]));

        return redirect(route('penalites.index'));
    }

    /**
     * Display the specified Penalite.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $penalite = $this->penaliteRepository->find($id);

        if (empty($penalite)) {
            Flash::error(__('messages.not_found', ['model' => __('models/penalites.singular')]));

            return redirect(route('penalites.index'));
        }

        return view('penalites.show')->with('penalite', $penalite);
    }

    /**
     * Show the form for editing the specified Penalite.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $penalite = $this->penaliteRepository->find($id);

        if (empty($penalite)) {
            Flash::error(__('messages.not_found', ['model' => __('models/penalites.singular')]));

            return redirect(route('penalites.index'));
        }

        return view('penalites.edit')->with('penalite', $penalite);
    }

    /**
     * Update the specified Penalite in storage.
     *
     * @param int $id
     * @param UpdatePenaliteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePenaliteRequest $request)
    {
        $penalite = $this->penaliteRepository->find($id);

        if (empty($penalite)) {
            Flash::error(__('messages.not_found', ['model' => __('models/penalites.singular')]));

            return redirect(route('penalites.index'));
        }

        $penalite = $this->penaliteRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/penalites.singular')]));

        return redirect(route('penalites.index'));
    }

    /**
     * Remove the specified Penalite from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $penalite = $this->penaliteRepository->find($id);

        if (empty($penalite)) {
            Flash::error(__('messages.not_found', ['model' => __('models/penalites.singular')]));

            return redirect(route('penalites.index'));
        }

        $this->penaliteRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/penalites.singular')]));

        return redirect(route('penalites.index'));
    }
}
