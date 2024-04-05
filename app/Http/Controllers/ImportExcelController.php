<?php

namespace App\Http\Controllers;

use App\DataTables\ImportExcelDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateImportExcelRequest;
use App\Http\Requests\UpdateImportExcelRequest;
use App\Repositories\ImportExcelRepository;
use App\Http\Controllers\AppBaseController;
use App\Imports\ExcelImportClass;
use Illuminate\Support\Facades\Session;
use App\Models\Importcalendar;
use App\Models\Penalite;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ImportExcel;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Importer;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Event;
use App\Models\Chauffeur;
use App\Models\PenaliteChauffeur;
use Response;
use Excel;

class ImportExcelController extends AppBaseController
{
    /** @var ImportExcelRepository $importExcelRepository*/
    private $importExcelRepository;

    public function __construct(ImportExcelRepository $importExcelRepo)
    {
        $this->importExcelRepository = $importExcelRepo;
    }

    /**
     * Display a listing of the ImportExcel.
     *
     * @param ImportExcelDataTable $importExcelDataTable
     *
     * @return Response
     */
    public function index(ImportExcelDataTable $importExcelDataTable, $id = null)
    {
        if(Session::has('success')){
            Alert::success(__('messages.saved', ['model' => __('models/importExcels.singular')]));
            Session::forget('success');
        }

        if(Session::has('updated')){
            Alert::success(__('messages.updated', ['model' => __('models/importExcels.singular')]));
            Session::forget('updated');
        }

        if(Session::has('deleted')){
            Alert::success(__('messages.deleted', ['model' => __('models/importExcels.singular')]));
            Session::forget('deleted');
        }
        if ($id !== null) {
            return $this->detail_liste_importation($id, $importExcelDataTable);
        }

        return $importExcelDataTable->render('import_excels.index');
    }




    public function associateEventWithJourney(Request $request){
        $chauffeur = $request->input('chauffeur');
        $month = $request->input('mois');
        
        $drive = getDriverByName($chauffeur);
        $events = getEventMonthly($chauffeur, $month);
        $livraisons = getJourneyOfDriverMonthly($chauffeur, $month);

        $results = [];
        $penalites = [];
        

        // Associer les événements aux livraisons correspondantes
        foreach ($livraisons as $livraison) {
            // Récupérer les événements déclenchés pendant cette livraison
            $evenementsLivraison = $events->filter(function ($event) use ($livraison) {
                $eventDate = Carbon::parse($event->date)->startOfDay();
                $debutLivraisonDate = Carbon::parse($livraison->date_debut)->startOfDay();

                if ($livraison->date_fin === null) {
                    return $eventDate->eq($debutLivraisonDate); // Utilise gte pour inclure la date de début de livraison
                } else {
                    $finLivraisonDate = Carbon::parse($livraison->date_fin)->startOfDay();
                    return $eventDate->between($debutLivraisonDate, $finLivraisonDate);
                }
            });


            foreach ($evenementsLivraison as $event){
                $typeEvent = $event->type;
                $penalite = Penalite::where('event', $typeEvent)->first(); // Assume qu'il n'y a qu'une seule pénalité par événement
                if ($penalite) {
                    $penalites[$event->id] = $penalite->point_penalite; // Stockez le point de pénalité associé à l'événement
                }
                // Enregistrer dans la table Penalité chauffeur

                $penality = PenaliteChauffeur::updateOrCreate([
                    'id_chauffeur' => $drive->id,
                    'id_calendar' => $livraison->id,
                    'id_event' => $event->id,
                    'id_penalite' => $penalite->id,
                    'date' => $event->date,
                ]);
            }

            
            // Ajouter la livraison avec les événements correspondants au tableau
            $results[] = [
                'livraison' => $livraison,
                'evenements' => $evenementsLivraison,
                'penalites' => $penalites,
            ];
        }
        $point_total = getPointPenaliteTotalMonthly($drive->id, $month);
        
        return view('events.resultats', compact('results', 'point_total'));
    }


    /**
     * Show the form for creating a new ImportExcel.
     *
     * @return Response
     */
    public function create()
    {
        return view('import_excels.create');
    }

    /**
     * Store a newly created ImportExcel in storage.
     *
     * @param CreateImportExcelRequest $request
     *
     * @return Response
     */
    public function store(CreateImportExcelRequest $request)
    {
        $input = $request->all();

        $importExcel = $this->importExcelRepository->create($input);

        // Alert::success(__('messages.saved', ['model' => __('models/importExcels.singular')]));
        Session::put('success', 'success');

        return redirect(route('importExcels.index'));
    }

    /**
     * Display the specified ImportExcel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $importExcel = $this->importExcelRepository->find($id);

        if (empty($importExcel)) {
            Alert::error(__('messages.not_found', ['model' => __('models/importExcels.singular')]));

            return redirect(route('importExcels.index'));
        }

        return view('import_excels.show')->with('importExcel', $importExcel);
    }

    /**
     * Show the form for editing the specified ImportExcel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $importExcel = $this->importExcelRepository->find($id);

        if (empty($importExcel)) {
            Alert::error(__('messages.not_found', ['model' => __('models/importExcels.singular')]));

            return redirect(route('importExcels.index'));
        }

        return view('import_excels.edit')->with('importExcel', $importExcel);
    }

    /**
     * Update the specified ImportExcel in storage.
     *
     * @param int $id
     * @param UpdateImportExcelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImportExcelRequest $request)
    {
        $importExcel = $this->importExcelRepository->find($id);

        if (empty($importExcel)) {
            Alert::error(__('messages.not_found', ['model' => __('models/importExcels.singular')]));

            return redirect(route('importExcels.index'));
        }

        $importExcel = $this->importExcelRepository->update($request->all(), $id);

        // Alert::success(__('messages.updated', ['model' => __('models/importExcels.singular')]));
        Session::put('updated', 'updated');

        return redirect(route('importExcels.index'));
    }

    /**
     * Remove the specified ImportExcel from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $importExcel = $this->importExcelRepository->find($id);

        if (empty($importExcel)) {
            Alert::error(__('messages.not_found', ['model' => __('models/importExcels.singular')]));

            return redirect(route('importExcels.index'));
        }

        $this->importExcelRepository->delete($id);

        // Alert::success(__('messages.deleted', ['model' => __('models/importExcels.singular')]));
        Session::put('deleted', 'deleted');

        return redirect(route('importExcels.index'));
    }


    /**
     * Liste des fichiers importer.
     *
     * @return Response
     */
    public function affichage_import()
    {
        return view('import_excels.import');
    }

    
    /**
     * redirection vers affichage import excel.
     *
     * @return Response
     */
    public function liste_importation()
    {
        // ImportExcel::groupBy('id')
        $distinctNames = ImportExcel::groupBy('name_importation')->pluck('name_importation');
     
        return view('import_excels.liste_import.blade', compact('distinctNames'));
    }


    /**
     * Importation fichier excel
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import_excel(Request $request)
    {
        // Verfication de l'extension du fichier 
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);
        $file = $request->file('excel_file');  

        //Recupération du nom du fichier excel
        $nomCompletFichierExcel = $file->getClientOriginalName();
        $nomFichier = pathinfo($nomCompletFichierExcel, PATHINFO_FILENAME);

        $verification = Importcalendar::where('name',$nomFichier)->first();

        if($verification!=null){
            return redirect()->back()->with('alert', 'Ce fichier a été déjà importé.');
        }

            
        // Enregistrement de l'import calendar avec le nom du fichier
        $import_calendar = new Importcalendar([
            "name" => $nomFichier
        ]);
        $import_calendar->save();
        
        // Ajout du nom du fichier importer 
        $import = new ExcelImportClass($nomFichier,$import_calendar->id);
        Excel::import($import, $file);

        //Recuperation de la date debut et fin du fichier inserer
        $date_debut = ImportExcel::where('import_calendar_id', $import_calendar->id)->first('date_debut');

        $max_id_import_excel = ImportExcel::where('import_calendar_id',  $import_calendar->id)->max('id');
        $date_finals = ImportExcel::where('id',$max_id_import_excel)->first('date_fin');

        if($date_finals->date_fin == null){
            $date_fin_fichier = ImportExcel::where('id',$max_id_import_excel)->first('date_debut');
            $date_finals = $date_fin_fichier->date_debut;
        }else{
            $date_finals = $date_finals->date_fin;
        }

        $import_calendar->update([
            'date_debut' => $date_debut->date_debut,
            'date_fin' => $date_finals
        ]);


        Alert::success(__('Importation réussie'));

        return redirect(route('importcalendars.index'));
    }
    

    /**
     * Display a listing of the ImportExcel filtered by id.
     *
     * @param int $id
     * @param ImportExcelDataTable $importExcelDataTable
     * @return Response
     */
    public function detail_liste_importation($id, ImportExcelDataTable $importExcelDataTable)
    {
        return $importExcelDataTable->with('id', $id)->render('import_excels.index');
    }
    

}
