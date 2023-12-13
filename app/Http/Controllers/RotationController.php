<?php

namespace App\Http\Controllers;

use App\DataTables\RotationDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateRotationRequest;
use App\Http\Requests\UpdateRotationRequest;
use Carbon\Carbon;
use App\Repositories\RotationRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Rotation;
use Carbon\CarbonInterval;
use Response;

class RotationController extends AppBaseController
{
    /** @var RotationRepository $rotationRepository*/
    private $rotationRepository;

    public function __construct(RotationRepository $rotationRepo)
    {
        $this->rotationRepository = $rotationRepo;
    }

    /**
     * Display a listing of the Rotation.
     *
     * @param RotationDataTable $rotationDataTable
     *
     * @return Response
     */
    public function index(RotationDataTable $rotationDataTable)
    {
        $immatriculation1 = "3993AH";
        $immatriculation2 = "9345TK";
        $totalRotationIbity = calculerDureeTotale($immatriculation1);
        $totalRotationTamatave = calculerDureeTotale($immatriculation2);

        // // Les heures de début et de fin (à remplacer par vos valeurs réelles)
        // $heureDebut = Carbon::parse('2023-11-11 07:14:05');
        // $heureFin = Carbon::parse('2023-11-15 03:13:05');

        // // Calculer la différence en heures
        // $differenceEnHeures = $heureDebut->diffInHours($heureFin);
        // dd($differenceEnHeures);

        // $rotations = Rotation::where('matricule', '3993AH')
        // ->orderBy('date_heur')
        // ->get();

        // // Regrouper les rotations par tranche
        // $rotationsParTranche = $rotations->groupBy('tranche');

        // // Calculer la différence en heures entre la première et la dernière rotation pour chaque tranche
        // $differencesParTranche = $rotationsParTranche->map(function ($group) {
        //     $premiereRotation = $group->first();
        //     $derniereRotation = $group->last();

        //     if ($premiereRotation && $derniereRotation) {
        //         $differenceEnHeures = $premiereRotation->date_heur->diffInHours($derniereRotation->date_heur);
        //         return $differenceEnHeures;
        //     }

        //     return null;
        // });

        // // dd($differencesParTranche);

        // // Afficher les différences par tranche
        // $differencesParTranche->each(function ($difference, $tranche) {
        //     echo "Tranche $tranche : " . ($difference ?? 'Non disponible') . " heures\n";
        // });
        
        return $rotationDataTable->render('rotations.index', compact('totalRotationIbity', 'totalRotationTamatave'));
    }

    /**
     * Show the form for creating a new Rotation.
     *
     * @return Response
     */
    public function create()
    {
        return view('rotations.create');
    }

    /**
     * Store a newly created Rotation in storage.
     *
     * @param CreateRotationRequest $request
     *
     * @return Response
     */
    public function store(CreateRotationRequest $request)
    {
        $input = $request->all();

        $rotation = $this->rotationRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/rotations.singular')]));

        return redirect(route('rotations.index'));
    }

    /**
     * Display the specified Rotation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $rotation = $this->rotationRepository->find($id);

        if (empty($rotation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/rotations.singular')]));

            return redirect(route('rotations.index'));
        }

        return view('rotations.show')->with('rotation', $rotation);
    }


    /**
     * Show the form for editing the specified Rotation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $rotation = $this->rotationRepository->find($id);

        if (empty($rotation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/rotations.singular')]));

            return redirect(route('rotations.index'));
        }

        return view('rotations.edit')->with('rotation', $rotation);
    }

    /**
     * Update the specified Rotation in storage.
     *
     * @param int $id
     * @param UpdateRotationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRotationRequest $request)
    {
        $rotation = $this->rotationRepository->find($id);
        $data = $request->all();

        if (isset($data['date_heur'])) {
            $data['date_heur'] = Carbon::parse($data['date_heur'])->format('Y-m-d H:i:s');
        }

        if (empty($rotation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/rotations.singular')]));

            return redirect(route('rotations.index'));
        }
       

        $rotation = $this->rotationRepository->update($data, $id);

        Flash::success(__('messages.updated', ['model' => __('models/rotations.singular')]));

        return redirect(route('rotations.index'));
    }

    /**
     * Remove the specified Rotation from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $rotation = $this->rotationRepository->find($id);

        if (empty($rotation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/rotations.singular')]));

            return redirect(route('rotations.index'));
        }

        $this->rotationRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/rotations.singular')]));

        return redirect(route('rotations.index'));
    }
}
