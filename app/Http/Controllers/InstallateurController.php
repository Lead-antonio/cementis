<?php

namespace App\Http\Controllers;

use App\DataTables\InstallateurDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateInstallateurRequest;
use App\Http\Requests\UpdateInstallateurRequest;
use App\Repositories\InstallateurRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class InstallateurController extends AppBaseController
{
    /** @var InstallateurRepository $installateurRepository*/
    private $installateurRepository;

    public function __construct(InstallateurRepository $installateurRepo)
    {
        $this->installateurRepository = $installateurRepo;
    }

    /**
     * Display a listing of the Installateur.
     *
     * @param InstallateurDataTable $installateurDataTable
     *
     * @return Response
     */
    public function index(InstallateurDataTable $installateurDataTable)
    {
        return $installateurDataTable->render('installateurs.index');
    }

    /**
     * Show the form for creating a new Installateur.
     *
     * @return Response
     */
    public function create()
    {
        return view('installateurs.create');
    }

    /**
     * Store a newly created Installateur in storage.
     *
     * @param CreateInstallateurRequest $request
     *
     * @return Response
     */
    public function store(CreateInstallateurRequest $request)
    {
        $input = $request->all();

        $installateur = $this->installateurRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/installateurs.singular')]));

        return redirect(route('installateurs.index'));
    }

    /**
     * Display the specified Installateur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $installateur = $this->installateurRepository->find($id);

        if (empty($installateur)) {
            Flash::error(__('messages.not_found', ['model' => __('models/installateurs.singular')]));

            return redirect(route('installateurs.index'));
        }

        return view('installateurs.show')->with('installateur', $installateur);
    }

    /**
     * Show the form for editing the specified Installateur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $installateur = $this->installateurRepository->find($id);

        if (empty($installateur)) {
            Flash::error(__('messages.not_found', ['model' => __('models/installateurs.singular')]));

            return redirect(route('installateurs.index'));
        }

        return view('installateurs.edit')->with('installateur', $installateur);
    }

    /**
     * Update the specified Installateur in storage.
     *
     * @param int $id
     * @param UpdateInstallateurRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInstallateurRequest $request)
    {
        $installateur = $this->installateurRepository->find($id);

        if (empty($installateur)) {
            Flash::error(__('messages.not_found', ['model' => __('models/installateurs.singular')]));

            return redirect(route('installateurs.index'));
        }

        $installateur = $this->installateurRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/installateurs.singular')]));

        return redirect(route('installateurs.index'));
    }

    /**
     * Remove the specified Installateur from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $installateur = $this->installateurRepository->find($id);

        if (empty($installateur)) {
            Flash::error(__('messages.not_found', ['model' => __('models/installateurs.singular')]));

            return redirect(route('installateurs.index'));
        }

        $this->installateurRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/installateurs.singular')]));

        return redirect(route('installateurs.index'));
    }
}
