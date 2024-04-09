<?php

namespace App\Http\Controllers;

use App\DataTables\PenaliteChauffeurDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePenaliteChauffeurRequest;
use App\Http\Requests\UpdatePenaliteChauffeurRequest;
use App\Repositories\PenaliteChauffeurRepository;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\AppBaseController;
use App\Models\Event;
use App\Models\Chauffeur;
use App\Models\ImportExcel;
use App\Models\Penalite;
use App\Models\PenaliteChauffeur;
use Illuminate\Support\Carbon;
use Response;

class PenaliteChauffeurController extends AppBaseController
{
    /** @var PenaliteChauffeurRepository $penaliteChauffeurRepository*/
    private $penaliteChauffeurRepository;

    public function __construct(PenaliteChauffeurRepository $penaliteChauffeurRepo)
    {
        $this->penaliteChauffeurRepository = $penaliteChauffeurRepo;
    }

    /**
     * Display a listing of the PenaliteChauffeur.
     *
     * @param PenaliteChauffeurDataTable $penaliteChauffeurDataTable
     *
     * @return Response
     */
    public function index(PenaliteChauffeurDataTable $penaliteChauffeurDataTable)
    {
        $importExcelRows = ImportExcel::all();
        // dd($importExcelRows);
        $events = Event::all();

        foreach ($importExcelRows as $importRow) {
                $dateDebut = Carbon::parse($importRow->date_debut);
                $dateFin = $importRow->date_fin ? Carbon::parse($importRow->date_fin) : null;
                // Récupérer les événements déclenchés pendant cette livraison
                $eventsDuringDelivery = $events->filter(function ($event) use ($dateDebut, $dateFin) {
                    $eventDate = Carbon::parse($event->date);
                    if ($dateFin === null) {
                        return  $eventDate->eq($dateDebut);
                    } else {
                        return  $eventDate->between($dateDebut, $dateFin);
                    }
                });
        
                foreach ($eventsDuringDelivery as $event){
                    $typeEvent = $event->type;
                    $penalite = Penalite::where('event', $typeEvent)->first();
                    // Enregistrer dans la table Penalité chauffeur
        
                    $penality = PenaliteChauffeur::updateOrCreate([
                        // 'id_chauffeur' => $chauffeur->id,
                        'id_calendar' => $importRow->id,
                        'id_event' => $event->id,
                        'id_penalite' => $penalite->id,
                        'date' => $event->date,
                    ]);
                }
        }        
        return $penaliteChauffeurDataTable->render('penalite_chauffeurs.index');
    }

    /**
     * Show the form for creating a new PenaliteChauffeur.
     *
     * @return Response
     */
    public function create()
    {
        return view('penalite_chauffeurs.create');
    }

    /**
     * Store a newly created PenaliteChauffeur in storage.
     *
     * @param CreatePenaliteChauffeurRequest $request
     *
     * @return Response
     */
    public function store(CreatePenaliteChauffeurRequest $request)
    {
        $input = $request->all();

        $penaliteChauffeur = $this->penaliteChauffeurRepository->create($input);

        Alert::success(__('messages.saved', ['model' => __('models/penaliteChauffeurs.singular')]));

        return redirect(route('penaliteChauffeurs.index'));
    }

    /**
     * Display the specified PenaliteChauffeur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $penaliteChauffeur = $this->penaliteChauffeurRepository->find($id);

        if (empty($penaliteChauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/penaliteChauffeurs.singular')]));

            return redirect(route('penaliteChauffeurs.index'));
        }

        return view('penalite_chauffeurs.show')->with('penaliteChauffeur', $penaliteChauffeur);
    }

    /**
     * Show the form for editing the specified PenaliteChauffeur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $penaliteChauffeur = $this->penaliteChauffeurRepository->find($id);

        if (empty($penaliteChauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/penaliteChauffeurs.singular')]));

            return redirect(route('penaliteChauffeurs.index'));
        }

        return view('penalite_chauffeurs.edit')->with('penaliteChauffeur', $penaliteChauffeur);
    }

    /**
     * Update the specified PenaliteChauffeur in storage.
     *
     * @param int $id
     * @param UpdatePenaliteChauffeurRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePenaliteChauffeurRequest $request)
    {
        $penaliteChauffeur = $this->penaliteChauffeurRepository->find($id);

        if (empty($penaliteChauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/penaliteChauffeurs.singular')]));

            return redirect(route('penaliteChauffeurs.index'));
        }

        $penaliteChauffeur = $this->penaliteChauffeurRepository->update($request->all(), $id);

        Alert::success(__('messages.updated', ['model' => __('models/penaliteChauffeurs.singular')]));

        return redirect(route('penaliteChauffeurs.index'));
    }

    /**
     * Remove the specified PenaliteChauffeur from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $penaliteChauffeur = $this->penaliteChauffeurRepository->find($id);

        if (empty($penaliteChauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/penaliteChauffeurs.singular')]));

            return redirect(route('penaliteChauffeurs.index'));
        }

        $this->penaliteChauffeurRepository->delete($id);

        Alert::success(__('messages.deleted', ['model' => __('models/penaliteChauffeurs.singular')]));

        return redirect(route('penaliteChauffeurs.index'));
    }
}
