<?php

namespace App\Http\Controllers;

use App\DataTables\ChauffeurDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateChauffeurRequest;
use App\Http\Requests\UpdateChauffeurRequest;
use App\Repositories\ChauffeurRepository;
use App\Http\Controllers\AppBaseController;
use RealRashid\SweetAlert\Facades\Alert;
use Response;

class ChauffeurController extends AppBaseController
{
    /** @var ChauffeurRepository $chauffeurRepository*/
    private $chauffeurRepository;

    public function __construct(ChauffeurRepository $chauffeurRepo)
    {
        $this->chauffeurRepository = $chauffeurRepo;
    }

    /**
     * Display a listing of the Chauffeur.
     *
     * @param ChauffeurDataTable $chauffeurDataTable
     *
     * @return Response
     */
    public function index(ChauffeurDataTable $chauffeurDataTable)
    {
        return $chauffeurDataTable->render('chauffeurs.index');
    }

    /**
     * Show the form for creating a new Chauffeur.
     *
     * @return Response
     */
    public function create()
    {
        return view('chauffeurs.create');
    }

    /**
     * Store a newly created Chauffeur in storage.
     *
     * @param CreateChauffeurRequest $request
     *
     * @return Response
     */
    public function store(CreateChauffeurRequest $request)
    {
        $input = $request->all();

        $chauffeur = $this->chauffeurRepository->create($input);

        Alert::success(__('messages.saved', ['model' => __('models/chauffeurs.singular')]));

        return redirect(route('chauffeurs.index'));
    }

    /**
     * Display the specified Chauffeur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $chauffeur = $this->chauffeurRepository->find($id);

        if (empty($chauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

            return redirect(route('chauffeurs.index'));
        }

        return view('chauffeurs.show')->with('chauffeur', $chauffeur);
    }

    /**
     * Show the form for editing the specified Chauffeur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $chauffeur = $this->chauffeurRepository->find($id);

        if (empty($chauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

            return redirect(route('chauffeurs.index'));
        }

        return view('chauffeurs.edit')->with('chauffeur', $chauffeur);
    }

    /**
     * Update the specified Chauffeur in storage.
     *
     * @param int $id
     * @param UpdateChauffeurRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChauffeurRequest $request)
    {
        $chauffeur = $this->chauffeurRepository->find($id);

        if (empty($chauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

            return redirect(route('chauffeurs.index'));
        }

        $chauffeur = $this->chauffeurRepository->update($request->all(), $id);

        Alert::success(__('messages.updated', ['model' => __('models/chauffeurs.singular')]));

        return redirect(route('chauffeurs.index'));
    }

    /**
     * Remove the specified Chauffeur from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $chauffeur = $this->chauffeurRepository->find($id);

        if (empty($chauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

            return redirect(route('chauffeurs.index'));
        }

        $this->chauffeurRepository->delete($id);

        Alert::success(__('messages.deleted', ['model' => __('models/chauffeurs.singular')]));

        return redirect(route('chauffeurs.index'));
    }
}
