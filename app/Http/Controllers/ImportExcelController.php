<?php

namespace App\Http\Controllers;

use App\DataTables\ImportExcelDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateImportExcelRequest;
use App\Http\Requests\UpdateImportExcelRequest;
use App\Repositories\ImportExcelRepository;
use App\Http\Controllers\AppBaseController;
use App\Imports\ExcelImportClass;
use App\Models\Importcalendar;
use App\Models\Penalite;
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
        if ($id !== null) {
            return $this->detail_liste_importation($id, $importExcelDataTable);
        }

        return $importExcelDataTable->render('import_excels.index');
    }

    public function getTrajetOfDriverMonthly($chauffeur, $month){
        $livraisons = ImportExcel::where('rfid_chauffeur', $chauffeur)
            ->whereMonth('date_debut', $month)
            ->get();

        return $livraisons;

    }

    public function associateEventWithPenality($evenements){
        $penalites = [];

            foreach ($evenements as $event){
                $typeEvent = $event->type;
                $penalite = Penalite::where('event', $typeEvent)->first(); // Assume qu'il n'y a qu'une seule pénalité par événement
                if ($penalite) {
                    $penalites[$event->id] = $penalite->point_penalite; // Stockez le point de pénalité associé à l'événement
                }
            }

        return $penalites;
    }


    public function associateEventWithJourney(Request $request){
        $chauffeur = $request->input('chauffeur');
        $month = $request->input('mois');
        
        $eventInstance = new Event();
        $driverInstance = new Chauffeur();
        $drive = $driverInstance->getDriverByName($chauffeur);
        $events = $eventInstance->getEventMonthly($chauffeur, $month);
        $livraisons = $this->getTrajetOfDriverMonthly($chauffeur, $month);

        $results = [];

        // Associer les événements aux livraisons correspondantes
        foreach ($livraisons as $livraison) {
            // Récupérer les événements déclenchés pendant cette livraison
            $evenementsLivraison = $events->filter(function ($event) use ($livraison) {
                if ($livraison->date_fin === null) {
                    return $event->date = $livraison->date_debut;
                } else {
                    return $event->date >= $livraison->date_debut &&
                           $event->date <= $livraison->date_fin;
                }
            });


            foreach ($evenementsLivraison as $event){
                $typeEvent = $event->type;
                $penalite = Penalite::where('event', $typeEvent)->first(); // Assume qu'il n'y a qu'une seule pénalité par événement
                if ($penalite) {
                    $penalites[$event->id] = $penalite->point_penalite; // Stockez le point de pénalité associé à l'événement
                }
                // Enregistrer dans la table Penalité chauffeur

                $penality = PenaliteChauffeur::firstOrCreate([
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

        
        return view('events.resultats', compact('results'));
    }

    public function calendar($rfid, $date_debut, $date_fin){
        $valeur_retour = 0;

        if($date_debut !== null && $date_fin !== null){
            $dataExcel = ImportExcel::where('rfid_chauffeur', $rfid)
                ->where('date_debut', '<=', $date_fin)
                ->where('date_fin', '>=', $date_debut)
                ->get();
            if(!$dataExcel->isEmpty()){
                $valeur_retour = 1;
            }
        } elseif(($date_debut !== null && $date_fin === null)) {
            $dataExcel = ImportExcel::where('rfid_chauffeur', $rfid)
                        ->where('date_debut', '=', $date_debut)
                        ->whereNull('date_fin')
                        ->get();        
            if(!$dataExcel->isEmpty()){
                $valeur_retour = 1;
            }
        }
        return $valeur_retour;
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

        Alert::success(__('messages.saved', ['model' => __('models/importExcels.singular')]));

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

        Alert::success(__('messages.updated', ['model' => __('models/importExcels.singular')]));

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

        Alert::success(__('messages.deleted', ['model' => __('models/importExcels.singular')]));

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
