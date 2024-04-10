<?php

namespace App\Http\Controllers;

use App\DataTables\TransporteurDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTransporteurRequest;
use App\Http\Requests\UpdateTransporteurRequest;
use App\Repositories\TransporteurRepository;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\AppBaseController;
use App\Models\Chauffeur;
use Response;

class TransporteurController extends AppBaseController
{
    /** @var TransporteurRepository $transporteurRepository*/
    private $transporteurRepository;

    public function __construct(TransporteurRepository $transporteurRepo)
    {
        $this->transporteurRepository = $transporteurRepo;
    }

    /**
     * Display a listing of the Transporteur.
     *
     * @param TransporteurDataTable $transporteurDataTable
     *
     * @return Response
     */
    public function index(TransporteurDataTable $transporteurDataTable)
    {
        return $transporteurDataTable->render('transporteurs.index');
    }

    /**
     * Show the form for creating a new Transporteur.
     *
     * @return Response
     */
    public function create()
    {
        return view('transporteurs.create');
    }

    /**
     * Store a newly created Transporteur in storage.
     *
     * @param CreateTransporteurRequest $request
     *
     * @return Response
     */
    public function store(CreateTransporteurRequest $request)
    {
        $input = $request->all();
        $transporteur = $this->transporteurRepository->create($input);

        Alert::success(__('messages.saved', ['model' => __('models/transporteurs.singular')]));

        return redirect(route('transporteurs.index'));
    }

    /**
     * Display the specified Transporteur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $transporteur = $this->transporteurRepository->find($id);
        $chauffeur = Chauffeur::all();

        if (empty($transporteur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/transporteurs.singular')]));
            return redirect(route('transporteurs.index'));
        }

        return view('transporteurs.show')
        ->with('transporteur', $transporteur)
        ->with('chauffeur', $chauffeur);
    }

    /**
     * Show the form for editing the specified Transporteur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $transporteur = $this->transporteurRepository->find($id);

        if (empty($transporteur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/transporteurs.singular')]));

            return redirect(route('transporteurs.index'));
        }

        return view('transporteurs.edit')->with('transporteur', $transporteur);
    }

    /**
     * Update the specified Transporteur in storage.
     *
     * @param int $id
     * @param UpdateTransporteurRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransporteurRequest $request)
    {
        $transporteur = $this->transporteurRepository->find($id);

        if (empty($transporteur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/transporteurs.singular')]));

            return redirect(route('transporteurs.index'));
        }

        $transporteur = $this->transporteurRepository->update($request->all(), $id);

        Alert::success(__('messages.updated', ['model' => __('models/transporteurs.singular')]));

        return redirect(route('transporteurs.index'));
    }

    /**
     * Remove the specified Transporteur from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $transporteur = $this->transporteurRepository->find($id);

        if (empty($transporteur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/transporteurs.singular')]));

            return redirect(route('transporteurs.index'));
        }

        $this->transporteurRepository->delete($id);

        Alert::success(__('messages.deleted', ['model' => __('models/transporteurs.singular')]));

        return redirect(route('transporteurs.index'));
    }
}
