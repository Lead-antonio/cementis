<?php

namespace App\Http\Controllers;

use App\DataTables\EventDataTable;
use App\Exports\ScoringCardExport;
use App\Exports\ScoringExport;
use App\Http\Requests;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Repositories\EventRepository;
use App\Http\Controllers\AppBaseController;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Penalite;
use App\Models\Scoring;
use App\Models\ImportExcel;
use App\Models\Importcalendar;
use Dompdf\Dompdf;
use App\Models\Chauffeur;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class EventController extends AppBaseController
{
    /** @var EventRepository $eventRepository*/
    private $eventRepository;

    public function __construct(EventRepository $eventRepo)
    {
        $this->eventRepository = $eventRepo;
    }   

    public function viewScoring(){
        $drivers = Chauffeur::all()->pluck('nom')->toArray();
        
        return view('events.scoring', compact('drivers'));
    }

    /**
     * Display a listing of the Event.
     *
     * @param EventDataTable $eventDataTable
     *
     * @return Response
     */
    public function index(EventDataTable $eventDataTable)
    {
        // getInfractionWithmaximumPoint();
        return $eventDataTable->render('events.index');
    }


    public function saveScoring($data){
        foreach($data as $item){
            $existingScoring = Scoring::where('id_planning', $item['id_planning'])
                    ->where('driver_id', $item['driver_id'])
                    ->where('transporteur_id', $item['transporteur_id'])
                    ->first();
    
            if ($existingScoring) {
                if (empty($existingScoring->camion)) {
                    $existingScoring->camion = getPlateNumberByRfidAndTransporteur($existingScoring->driver_id, $existingScoring->transporteur_id);
                    $existingScoring->save();
                }
            }else{
                if (empty($item['camion'])) {
                    $item['camion'] = getPlateNumberByRfidAndTransporteur($item['driver_id'], $item['transporteur_id']);
                }

                Scoring::create([
                    'id_planning' => $item['id_planning'],
                    'driver_id' => $item['driver_id'],
                    'transporteur_id' => $item['transporteur_id'],
                    'camion' => $item['camion'],
                    'comment' => $item['comment'],
                    'distance' => $item['distance'],
                    'point' => $item['point'],
                ]);
            }
        }
    }

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

    public function newscoring(Request $request){
        $selectedPlanning = DB::table('import_calendar')->latest('id')->value('id');
        $existingScoring = Scoring::where('id_planning', $selectedPlanning)->exists();
        $import_calendar = Importcalendar::all();
        $alphaciment_driver = $request->query('alphaciment_driver', null);

        // if($existingScoring){
        //     $scoring = Scoring::where('id_planning', $selectedPlanning)->orderBy('point', 'desc')->get();
        // }else{
        //     $data = [];
        //     $createScoring = [];
            

        //     $results = scoring($selectedPlanning);
        //     if($results){
        //         foreach ($results as $result) {
        //             $driver = $result->driver;
        //             $event = $result->event;
        //             $camion = $result->camion;
        //             $transporteur = $result->transporteur;
        //             $total_point = $result->total_point;
                    
        //             if (!isset($data[$driver])) {
        //                 $data[$driver] = [
        //                     'transporteur' => $transporteur,
        //                     'total_point' => $total_point,
        //                     'Accélération brusque' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'Freinage brusque' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'Excès de vitesse hors agglomération' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'Excès de vitesse en agglomération' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'Survitesse excessive' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'Survitesse sur la piste d\'Ibity' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'Survitesse sur la piste de Tritriva' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'TEMPS DE CONDUITE CONTINUE NUIT' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'TEMPS DE CONDUITE CONTINUE JOUR' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'Temps de conduite maximum dans une journée de travail' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'Temps de repos hebdomadaire' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                     'Temps de repos minimum après une journée de travail' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 ];
        //                 $distance = getDistanceTotalDriverInCalendar($driver, $selectedPlanning);
        //                 // dd($distance);

        //                 $createScoring[] = [
        //                     'id_planning' => $selectedPlanning,
        //                     'driver_id' => $result->driver_id,
        //                     'transporteur_id' => $result->transporteur_id,
        //                     'driver' => $driver,
        //                     'transporteur' => $transporteur,
        //                     'camion' => $camion,
        //                     'comment' => '',
        //                     'distance' => $distance,
        //                     'point' => ($distance != 0) ? ($total_point / $distance) * 100 : 0
        //                 ];
        //             }
        
        //             $data[$driver][$event] = ['valeur' => $result->valeur, 'duree' => $result->duree, 'point' => $result->point];
        //         }
        //     }
        //     $this->saveScoring($createScoring);
        //     $scoring = Scoring::where('id_planning', $selectedPlanning)->orderBy('point', 'desc')->get();
        // }
        $scoring = Scoring::where('id_planning', $selectedPlanning)->orderBy('point', 'desc')->get();
        return view('events.scoring', compact('import_calendar', 'selectedPlanning', 'scoring','alphaciment_driver'));
    }

    public function ajaxHandle(Request $request){
        $selectedPlanning = $request->input('planning');
        
        $data = [];
        // $createScoring = [];
        // $results = scoring($selectedPlanning);
        // if($results){
        //     foreach ($results as $result) {
        //         $driver = $result->driver;
        //         $event = $result->event;
        //         $camion = $result->camion;
        //         $transporteur = $result->transporteur;
        //         $total_point = $result->total_point;
    
        //         if (!isset($data[$driver])) {
        //             $data[$driver] = [
        //                 'transporteur' => $transporteur,
        //                 'total_point' => $total_point,
        //                 'Accélération brusque' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'Freinage brusque' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'Excès de vitesse hors agglomération' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'Excès de vitesse en agglomération' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'Survitesse excessive' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'Survitesse sur la piste d\'Ibity' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'Survitesse sur la piste de Tritriva' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'Freinage brusque' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'TEMPS DE CONDUITE CONTINUE NUIT' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'TEMPS DE CONDUITE CONTINUE JOUR' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'Temps de conduite maximum dans une journée de travail' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'Temps de repos hebdomadaire' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //                 'Temps de repos minimum après une journée de travail' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
        //             ];
        //             $distance = getDistanceTotalDriverInCalendar($driver, $selectedPlanning);

        //             $createScoring[] = [
        //                 'id_planning' => $selectedPlanning,
        //                 'driver_id' => $result->driver_id,
        //                 'transporteur_id' => $result->transporteur_id,
        //                 'driver' => $driver,
        //                 'transporteur' => $transporteur,
        //                 'camion' => $camion,
        //                 'comment' => '',
        //                 'distance' => $distance,
        //                 'point' => $total_point
        //                 // 'point' => ($distance != 0) ? ($total_point / $distance) * 100 : 0
        //             ];
        //         }
    
        //         $data[$driver][$event] = ['valeur' => $result->valeur, 'duree' => $result->duree, 'point' => $result->point];
        //     }
        // }
        // $this->saveScoring($createScoring);
        $scoring = Scoring::where('id_planning', $selectedPlanning)->orderBy('point', 'desc')->get();
        return view('events.scoring_filtre', compact('data', 'selectedPlanning', 'scoring'));
    }

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
                $query->whereIn('camion', $camionsImport); // Ne garder que les camions présents dans ImportExcel
            } elseif ($alphaciment_driver === "non") {
                $query->whereNotIn('camion', $camionsImport); // Exclure ces camions
            }

        }

        $scoring = $query->orderBy('point', 'desc')->get();
        return view('events.scoring_filtre', compact('data', 'selectedPlanning', 'scoring'));
    }


    public function showMap($latitude, $longitude)
    {
        return view('events.map')->with(compact('latitude', 'longitude'));
    }

    public function TableauScoring($chauffeur, $id_planning){
        $scoring = tabScoringCard($chauffeur, $id_planning);
        
        return view('events.table_scoring', compact('scoring', 'id_planning', 'chauffeur'));
    }

    public function TableauScoringPdf(){
        $scoring = tabScoringCard();
        $total = totalScoringCard();
    
        $pdf = new Dompdf();
        $pdf->loadHtml(view('events.table_scoring', compact('scoring', 'total'))->render());
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        return $pdf->stream('tableau_scoring.pdf');
    }


    public function exportScoring($chauffeur, $id_planning)
    {
        try {
            
            // $scoring = tabScoringCard($chauffeur, $id_planning);
            $scoring = tabScoringCard_new($chauffeur, $id_planning);
            $distance_total = getDistanceTotalDriverInCalendar($chauffeur, $id_planning);
            return Excel::download(new ScoringExport($scoring, $distance_total ), 'scoring.xlsx');
        } catch (\Throwable $th) {

            // dd($th->getMessage());
            throw $th;
        }
    }

    /**
     * Fonction pour exporter les données scoringcard en un fichier excel
     * jonny
     */
    public function exportscoringcard(Request $request)
    {
        $planning = $request->planning ?? null;
        $alphaciment_driver = $request->alphaciment_driver ?? null;
        return Excel::download(new ScoringCardExport($planning,$alphaciment_driver), 'scoring_card.xlsx');
    }

    /**
     * Show the form for creating a new Event.
     *
     * @return Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created Event in storage.
     *
     * @param CreateEventRequest $request
     *
     * @return Response
     */
    public function store(CreateEventRequest $request)
    {
        $input = $request->all();

        $event = $this->eventRepository->create($input);

        Alert::success(__('messages.saved', ['model' => __('models/events.singular')]));

        return redirect(route('events.index'));
    }

    /**
     * Display the specified Event.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Alert::error(__('messages.not_found', ['model' => __('models/events.singular')]));

            return redirect(route('events.index'));
        }

        return view('events.show')->with('event', $event);
    }

    /**
     * Show the form for editing the specified Event.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Alert::error(__('messages.not_found', ['model' => __('models/events.singular')]));

            return redirect(route('events.index'));
        }

        return view('events.edit')->with('event', $event);
    }

    /**
     * Update the specified Event in storage.
     *
     * @param int $id
     * @param UpdateEventRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEventRequest $request)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Alert::error(__('messages.not_found', ['model' => __('models/events.singular')]));

            return redirect(route('events.index'));
        }

        $event = $this->eventRepository->update($request->all(), $id);

        Alert::success(__('messages.updated', ['model' => __('models/events.singular')]));

        return redirect(route('events.index'));
    }

    /**
     * Remove the specified Event from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Alert::error(__('messages.not_found', ['model' => __('models/events.singular')]));

            return redirect(route('events.index'));
        }

        $this->eventRepository->delete($id);

        Alert::success(__('messages.deleted', ['model' => __('models/events.singular')]));

        return redirect(route('events.index'));
    }
}
