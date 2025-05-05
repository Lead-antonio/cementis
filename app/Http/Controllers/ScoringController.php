<?php

namespace App\Http\Controllers;

use App\DataTables\ScoringDataTable;
use App\DataTables\DriverHaveNotScoringDataTable;
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
use App\Models\Chauffeur;
use App\Models\ChauffeurUpdate;
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

        $query = Scoring::where('id_planning', $selectedPlanning)->with(['driver', 'driver.latest_update']);;

        // Vérifier si $this->alphaciment_driver n'est pas null avant d'appliquer le filtre
        if ($alphaciment_driver !== null) {
            // Récupérer la liste des camions du ImportExcel en fonction du planning sélectionné
            // $camionsImport = ImportExcel::where('import_calendar_id', $selectedPlanning)
            //                     ->pluck('camion') // Récupère uniquement la colonne "camion"
            //                     ->toArray();

            // if ($alphaciment_driver === "oui") {
            //     // $query->whereIn('camion', $camionsImport); // Ne garder que les camions présents dans ImportExcel
            //     $query->where(function ($q) use ($camionsImport) {
            //         foreach ($camionsImport as $camion) {
            //             $q->orWhere('camion', 'LIKE', "%{$camion}%");
            //         }
            //     });
            // } elseif ($alphaciment_driver === "non") {
            //     // $query->whereNotIn('camion', $camionsImport); // Exclure ces camions
            //     $query->where(function ($q) use ($camionsImport) {
            //         foreach ($camionsImport as $camion) {
            //             $q->where('camion', 'NOT LIKE', "%{$camion}%");
            //         }
            //     });
            // }
            $badgesImport = ImportExcel::where('import_calendar_id', $selectedPlanning)
                                ->distinct()
                                ->pluck('badge_chauffeur') // Récupère uniquement la colonne "badge"
                                ->unique()
                                ->toArray();
            
            if ($alphaciment_driver === "oui") {
                $query->whereHas('driver', function($q) use ($badgesImport) {
                    $q->whereHas('latest_update', function($query) use ($badgesImport) {
                        $query->whereIn('numero_badge', $badgesImport);  // Filtre par badge en utilisant la relation latest_update
                    })
                    ->orWhereIn('numero_badge', $badgesImport);
                });
            } elseif ($alphaciment_driver === "non") {
                $query->whereHas('driver', function($q) use ($badgesImport) {
                    $q->whereHas('latest_update', function($query) use ($badgesImport) {
                        $query->whereNotIn('numero_badge', $badgesImport);  // Exclure les badges présents dans ImportExcel
                    })
                    ->orWhereNotIn('numero_badge', $badgesImport);
                });
            }

        }

        $scoring = $query->orderBy('point', 'desc')->get();
        return view('events.scoring_filtre', compact('data', 'selectedPlanning', 'scoring'));
    }

    // Antonio
    // List of driver having a scoring
    public function driver_has_scoring(Request $request){
        $selectedPlanning = $request->id_planning ?? DB::table('import_calendar')->latest('id')->value('id');

        $import_calendar = Importcalendar::all();
        $alphaciment_driver = 'oui';
        $badge_calendars = ImportExcel::where('import_calendar_id', $selectedPlanning)
        ->distinct()
        ->pluck('badge_chauffeur') // Récupère uniquement la colonne "camion"
        ->unique()
        ->toArray();

        // Récupérer les Scoring avec les chauffeurs et leurs mises à jour
        $scoring = Scoring::where('id_planning', $selectedPlanning)
        ->with('driver.latest_update') // Charger la dernière mise à jour des chauffeurs
        ->get();

        // Filtrer les Scoring en fonction des badges (mis à jour ou actuel)
        // $scoring = $scoringBadge->filter(function ($scoring) use ($badge_calendars) {
        //     // Récupérer le badge du chauffeur (mettre à jour ou actuel)
        //     $badge = $scoring->driver->latest_update ? $scoring->driver->latest_update->numero_badge : $scoring->driver->numero_badge;

        //     // Vérifier si le badge du chauffeur est présent dans la liste des badges
        //     return in_array(trim($badge), $badge_calendars);
        // });
                
        return view('events.scoring', compact('import_calendar', 'selectedPlanning', 'scoring','alphaciment_driver'));
    }

    // Antonio
    // List of driver having a scoring
    public function driver_have_not_scoring(DriverHaveNotScoringDataTable $dataTable, Request $request){
        $selectedPlanning = $request->id_planning ?? DB::table('import_calendar')->latest('id')->value('id');

        $badge_calendars = ImportExcel::where('import_calendar_id', $selectedPlanning)
        ->distinct()
        ->pluck('badge_chauffeur')
        ->unique()
        ->toArray();

        $badge_calendars = array_map('trim', $badge_calendars);

        $scoringBadge = Scoring::where('id_planning', $selectedPlanning)
            ->with('driver.latest_update')
            ->get();

        // Créer un tableau avec les badges des chauffeurs
        $badges_scoring = $scoringBadge->map(function($scoring) {
            // if ($scoring->driver->latest_update) {
            //     // Retourner le badge de la mise à jour, si elle existe
            //     return $scoring->driver->latest_update->numero_badge;
            // }
            
            // return $scoring->driver->numero_badge;
            return $scoring->badge_calendar;
        })->unique()->toArray();

        // Trouver les badges dans badge_calendars qui ne sont pas dans badges_scoring
        // $badge_not_in_scoring = array_diff($badge_calendars, $badges_scoring);
        $badge_not_in_scoring = [];

        // Vérifier si chaque badge de $badge_calendars est dans les $scoringBadges
        foreach ($badge_calendars as $badge) {
            if (!in_array(trim($badge), $badges_scoring)) {
                // Si le badge n'est pas dans les scoringBadges, on l'ajoute à la liste
                $badge_not_in_scoring[] = $badge;
            }
        }
        
        $data = $this->checkBadgesExist($badge_not_in_scoring);
        

        return $dataTable->with(['data' => $data])->render('scorings.driver_have_not_scoring');
    }

    public function checkBadgesExist(array $badges) {
        // Tableau pour stocker les résultats
        $result = [];

        // Vérifier chaque badge
        foreach ($badges as $badge) {
            // Chercher le chauffeur dans la table chauffeur
            $chauffeur = Chauffeur::where('numero_badge', $badge)->first();

            // Si le chauffeur est trouvé
            if ($chauffeur) {
                // Récupérer la dernière mise à jour du chauffeur s'il y en a une
                $latest_update = $chauffeur->latest_update()->first(); // Chercher la dernière mise à jour

                // Si une mise à jour existe
                if ($latest_update) {
                    $result[] = [
                        'id' => $latest_update->chauffeur_id,
                        'nom' => $latest_update->nom,
                        'numero_badge' => $latest_update->numero_badge ?? $chauffeur->numero_badge,
                        'observation' => 'Présent dans la base (chauffeur update)',
                        'update' => true
                    ];
                } else {
                    // Sinon, l'observation est basée sur le chauffeur directement
                    $result[] = [
                        'id' => $chauffeur->id,
                        'nom' => $chauffeur->nom,
                        'numero_badge' => $chauffeur->numero_badge,
                        'observation' => 'Présent dans la base (chauffeur)',
                        'update' => true,
                    ];
                }
            } else {
                // Si le chauffeur n'est pas trouvé, observation "pas dans la base"
                $result[] = [
                    'id' => null,
                    'nom' => null,
                    'numero_badge' => $badge,
                    'observation' => 'Badge non identifié',
                    'update' => false,
                ];
            }
        }

        return $result;
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
    public function export_excel_driver_Scoring($imei, $badge, $id_planning)
    {
        try {
            // $scoring = tabScoringCard_new($chauffeur, $id_planning);
            $scoring = driver_detail_scoring_card($imei, $badge, $id_planning);
            // $distance_total = getDistanceTotalDriverInCalendar($chauffeur, $id_planning);
            return Excel::download(new ScoringExport($scoring), 'scoring.xlsx');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Antonio
    // Detail of driver scoring
    public function driver_detail_scoring($imei, $badge, $id_planning){
        $scoring = driver_detail_scoring_card($imei, $badge, $id_planning);
        
        return view('events.driver_scoring', compact('scoring', 'id_planning', 'imei', 'badge'));
    }

    // Antonio
    // Detail of driver scoring
    public function truck_detail_scoring($immatricule, $id_planning){
        $scoring = truck_detail_scoring_card($immatricule, $id_planning);
        
        return view('events.truck_scoring', compact('scoring', 'id_planning', 'immatricule'));
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
