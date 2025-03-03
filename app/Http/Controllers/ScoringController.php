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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Penalite;
use App\Models\Scoring;
use App\Models\ImportExcel;
use App\Models\Importcalendar;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScoringCardExport;
use App\Exports\ScoringExport;

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

    // Antonio
    // Scoring card
    public function scoring_card(Request $request){
        $selectedPlanning = DB::table('import_calendar')->latest('id')->value('id');
        $existingScoring = Scoring::where('id_planning', $selectedPlanning)->exists();
        $import_calendar = Importcalendar::all();
        $alphaciment_driver = $request->query('alphaciment_driver', null);
        $scoring = Scoring::where('id_planning', $selectedPlanning)->orderBy('point', 'desc')->get();
        return view('events.scoring', compact('import_calendar', 'selectedPlanning', 'scoring','alphaciment_driver'));
    }

    // Antonio
    // Scoring card filtering by planning 
    public function filter_scoring_by_planning(Request $request){
        $selectedPlanning = $request->input('planning');
        
        $data = [];
        $scoring = Scoring::where('id_planning', $selectedPlanning)->orderBy('point', 'desc')->get();
        return view('events.scoring_filtre', compact('data', 'selectedPlanning', 'scoring'));
    }

    // Johnny
    // Filter scoring by has scoring ot haven't scoring
    public function FilterByTruckInCalendar(Request $request){
        $selectedPlanning = $request->input('planning');
        $alphaciment_driver = $request->input('alphaciment_driver');
        
        $data = [];

        $query = Scoring::where('id_planning', $selectedPlanning);

        // Vérifier si $this->alphaciment_driver n'est pas null avant d'appliquer le filtre
        if ($alphaciment_driver !== null) {
            // Récupérer la liste des camions du ImportExcel en fonction du planning sélectionné
            $camionsImport = ImportExcel::where('import_calendar_id', $selectedPlanning)
                                ->pluck('camion') // Récupère uniquement la colonne "camion"
                                ->toArray();

            if ($alphaciment_driver === "oui") {
                // $query->whereIn('camion', $camionsImport); // Ne garder que les camions présents dans ImportExcel
                $query->where(function ($q) use ($camionsImport) {
                    foreach ($camionsImport as $camion) {
                        $q->orWhere('camion', 'LIKE', "%{$camion}%");
                    }
                });
            } elseif ($alphaciment_driver === "non") {
                // $query->whereNotIn('camion', $camionsImport); // Exclure ces camions
                $query->where(function ($q) use ($camionsImport) {
                    foreach ($camionsImport as $camion) {
                        $q->where('camion', 'NOT LIKE', "%{$camion}%");
                    }
                });
            }

        }

        $scoring = $query->orderBy('point', 'desc')->get();
        return view('events.scoring_filtre', compact('data', 'selectedPlanning', 'scoring'));
    }

    // Antonio
    // List of driver having a scoring
    public function driver_has_scoring(Request $request){
        $selectedPlanning = DB::table('import_calendar')->latest('id')->value('id');

        $import_calendar = Importcalendar::all();
        $alphaciment_driver = 'oui';
        $camionsImport = ImportExcel::where('import_calendar_id', $selectedPlanning)
        ->pluck('camion') // Récupère uniquement la colonne "camion"
        ->toArray();

        $query = Scoring::where('id_planning', $selectedPlanning);
        $query->where(function ($q) use ($camionsImport) {
            foreach ($camionsImport as $camion) {
                $q->orWhere('camion', 'LIKE', "%{$camion}%");
            }
        });
        $scoring = $query->orderBy('point', 'desc')->get();
        
        return view('events.scoring', compact('import_calendar', 'selectedPlanning', 'scoring','alphaciment_driver'));
    }

    // Antonio
    // Comment on scoring card
    public function saveComments(Request $request)
    {
        $commentaires = $request->input('commentaire');
        

        foreach ($commentaires as $id => $commentaire) {
            $scoring = Scoring::find($id);
            if ($scoring) {
                $scoring->comment = $commentaire;
                $scoring->save();
            }
        }

        Alert::success('Succès', 'Commentaires enregistrés avec succès');
        return redirect()->back();
    }

     /**
    * jonny
     * Fonction pour exporter les données scoringcard en un fichier excel
     */
    public function export_excel_scoring_card(Request $request)
    {
        $planning = $request->planning ?? null;
        $alphaciment_driver = $request->alphaciment_driver ?? null;
        return Excel::download(new ScoringCardExport($planning,$alphaciment_driver), 'scoring_card.xlsx');
    }

    // Antonio
    // Export excel detail of driver score
    public function export_excel_driver_Scoring($chauffeur, $id_planning)
    {
        try {
            $scoring = tabScoringCard_new($chauffeur, $id_planning);
            $distance_total = getDistanceTotalDriverInCalendar($chauffeur, $id_planning);
            return Excel::download(new ScoringExport($scoring, $distance_total ), 'scoring.xlsx');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Antonio
    // Detail of driver scoring
    public function driver_detail_scoring($chauffeur, $id_planning){
        $scoring = tabScoringCard($chauffeur, $id_planning);
        
        return view('events.table_scoring', compact('scoring', 'id_planning', 'chauffeur'));
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
