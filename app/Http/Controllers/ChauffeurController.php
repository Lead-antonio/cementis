<?php

namespace App\Http\Controllers;

use App\DataTables\ChauffeurDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateChauffeurRequest;
use App\Http\Requests\UpdateChauffeurRequest;
use App\Models\Transporteur;
use App\Repositories\ChauffeurRepository;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Imports\DriverImportClass;
use App\Mail\ChauffeurDeleteMail;
use App\Models\Chauffeur;
use App\Models\ChauffeurUpdate;
use App\Models\ChauffeurUpdateStory;
use App\Models\ChauffeurUpdateType;
use App\Models\User;
use App\Models\Importcalendar;
use Illuminate\Support\Facades\DB;
use App\Models\Validation;
use App\Notifications\CreateChauffeurInfoNotification;
use App\Notifications\DeleteChauffeurInfoNotification;
use App\Notifications\UpdateChauffeurInfoNotification;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
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
    public function index(ChauffeurDataTable $chauffeurDataTable,  Request $request)
    {
        // $query = Chauffeur::query();
        $plannings = Importcalendar::all();
        $selected_planning =  DB::table('import_calendar')->latest('id')->first();

        // Si le paramètre 'non_fixe' est présent, filtre les chauffeurs
        if ($request->input('non_fixe') == 1) {
            $query->where('chauffeur.nom', 'chauffeur non fixe');
        }

        
        if(Session::has('success')){
            Alert::success(__('messages.saved', ['model' => __('models/chauffeurs.singular')]));
            Session::forget('success');
        }

        if(Session::has('updated')){
            Alert::success(__('messages.updated', ['model' => __('models/chauffeurs.singular')]));
            Session::forget('updated');
        }

        if(Session::has('deleted')){
            Alert::success(__('messages.deleted', ['model' => __('models/chauffeurs.singular')]));
            Session::forget('deleted');
        }
        return $chauffeurDataTable->render('chauffeurs.index', compact('plannings', 'selected_planning'));
        // return $chauffeurDataTable->withQuery($query)->render('chauffeurs.index');
    }

    /**
     * Show the form for creating a new Chauffeur.
     *
     * @return Response
     */
    public function create()
    {
        $transporteur = Transporteur::all();
        $action = "create";
        return view('chauffeurs.create', compact('transporteur', 'action'));
    }
    
    public function import_driver_excel(Request $request){
        try{
            // Verfication de l'extension du fichier 
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls'
            ]);
            $file = $request->file('excel_file'); 
            
            // Ajout du nom du fichier importer 
            $import = new DriverImportClass();
            Excel::import($import, $file);
            Alert::success(__('Importation réussie'));
            return redirect(route('chauffeurs.index'));
        }catch(\Exception $e){
            Alert::error(__('Erreur lors de l\'importation du fichier'));
        }
    }

    /**
     * Store a newly created Chauffeur in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try{
            $selectedPlanning = DB::table('import_calendar')->latest('id')->value('id');
            $validated = [
                "nom" => $request->nom,
                "transporteur_id" => $request->transporteur_id,
                "rfid_physique" => $request->rfid_physique,
                "contact" => $request->contact,
                "numero_badge" => $request->numero_badge,
                "id_planning" => $selectedPlanning,
            ];
 
            // Vérifier si une demande de création avec les mêmes données est déjà en attente
            $existingValidation = Validation::where('model_type', Chauffeur::class)
                ->where('status', 'pending')
                ->where('action_type', 'create')
                ->whereJsonContains('modifications', $validated) // Vérification du JSON stocké
                ->latest('created_at')
                ->first();

            if ($existingValidation) {
                Alert::warning('Erreur', 'Une demande de création pour  ce chauffeur  '. $request->nom .'  est déjà en attente.');
                return redirect()->back()->withInput(); // Retourner sur le formulaire avec les données saisies
            }

            $chauffeur =  Chauffeur::create($validated);

            $validation =  Validation::create([
                'operator_id' => auth()->id(),
                'model_type' => Chauffeur::class,
                'model_id' => $chauffeur->id,
                'modifications' => $validated,
                'status' => 'pending',
                'action_type' => 'create',
            ]);

            $admins = User::whereHas('roles', function ($query) {
                $query->where('name', 'supper-admin');
            })->get();
        
            Notification::send($admins, new CreateChauffeurInfoNotification($validation->operator->name,$chauffeur->nom));

            Alert::success('Succès', 'Votre demande de création a été envoyé!');

            return redirect(route('chauffeurs.index'));
        }catch(Exception $e){
            Alert::error('Erreur','Erreur :' . $e->getMessage());
            return redirect(route('chauffeurs.index'));
        }
    }


    // public function store(CreateChauffeurRequest $request)
    // {
    //     $input = $request->all();

    //     $chauffeur = $this->chauffeurRepository->create($input);

    //     Alert::success(__('messages.saved', ['model' => __('models/chauffeurs.singular')]));
    //     // Session::put('success', 'success');

    //     return redirect(route('chauffeurs.index'));
    // }

    /**
     * Display the specified Chauffeur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        // Récupérer le chauffeur de base
        $chauffeur = $this->chauffeurRepository->find($id);
    
        if (empty($chauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));
            return redirect(route('chauffeurs.index'));
        }
    
        // Récupérer les mises à jour triées par date d'installation décroissante
        $chauffeur_updates = ChauffeurUpdate::with('related_transporteur')
            ->where('chauffeur_id', $id)
            ->orderByDesc('date_installation')
            ->get();
    
        // Ajouter le chauffeur de base comme une ancienne mise à jour
        $chauffeur_base_as_update = new ChauffeurUpdate([
            'nom' => $chauffeur->nom,
            'rfid' => $chauffeur->rfid,
            'rfid_physique' => $chauffeur->rfid_physique,
            'numero_badge' => $chauffeur->numero_badge,
            'date_installation' => $chauffeur->created_at, // On suppose que le chauffeur de base a été créé à cette date
            'transporteur_id' => $chauffeur->transporteur_id, // On suppose que le chauffeur de base a été créé à cette date
            'contact' => $chauffeur->contact, // On suppose que le chauffeur de base a été créé à cette date
        ]);
    
        // Ajouter le chauffeur de base à la liste des mises à jour
        $chauffeur_updates->push($chauffeur_base_as_update);
    
        // Vérifier s'il existe des mises à jour
        if (!$chauffeur_updates->isEmpty()) {
            // Le chauffeur actuel est la dernière mise à jour
            $chauffeur_actuel = $chauffeur_updates->first();
            // Retirer la mise à jour actuelle de la liste des historiques
            $chauffeur_updates->shift();
        } else {
            // Si aucune mise à jour, le chauffeur de base reste le chauffeur actuel
            $chauffeur_actuel = $chauffeur;
        }
    
        return view('chauffeurs.show', compact('chauffeur_actuel', 'chauffeur_updates'));
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
        $transporteur = Transporteur::find($chauffeur->transporteur_id);
        $action = "edit";
        if (empty($chauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

            return redirect(route('chauffeurs.index'));
        }

        return view('chauffeurs.edit', compact('chauffeur', 'transporteur','action'));
    }

    /**
     * Show the form for editing the specified Chauffeur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit_story($id)
    {
        $chauffeur = $this->chauffeurRepository->find($id);
        $transporteur = Transporteur::pluck('nom', 'id');
        $action = "edit";
        $chauffeurUpdateTypes = ChauffeurUpdateType::where('id','<',4)->pluck('name', 'id'); // Récupère les types d'update
        
        // if (empty($chauffeur)) {
        //     Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

        //     return redirect(route('chauffeurs.index'));
        // }

        return view('chauffeurs.edit_story', compact('chauffeur', 'transporteur','chauffeurUpdateTypes','action'));
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

        // Alert::success(__('messages.updated', ['model' => __('models/chauffeurs.singular')]));
        Session::put('updated', 'updated');

        return redirect(route('chauffeurs.index'));
    }


    /**
     * Update the specified Chauffeur in storage.
     *
     *
     * @return Response
     */
    public function update_tranporteur_id(Request $request)
    {

        $transporteur_id = $request->transporteur_id;
        $chauffeur_id = $request->chauffeur;
        $chauffeurIdsInt = array_map('intval', $chauffeur_id);
        
        Chauffeur::whereIn('id',$chauffeurIdsInt)->update(['transporteur_id'=>$transporteur_id]);

        Alert::success(__('messages.updated', ['model' => __('models/clients.singular')]));

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

        // Alert::success(__('messages.deleted', ['model' => __('models/chauffeurs.singular')]));
        // Session::put('deleted', 'deleted');
        Alert::success(__('messages.updated', ['model' => __('Votre demande de suppression a')]));

        return redirect(route('chauffeurs.index'));
    }


    /**
     * Fonction qui recupere la demande de suppression d'un chauffeur 
     * jonny
     *
     * @param int $id
     *
     * @return Response
     */
    public function delete_sending(Request $request)
    {
        try{
            $id = $request->input('chauffeur_id'); // Récupère l'ID envoyé en POST
            $chauffeur = Chauffeur::findOrFail($id);

            if (empty($chauffeur)) {
                Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));
                return redirect(route('chauffeurs.index'));
            }

            // Vérifier si une demande de création avec les mêmes données est déjà en attente
            $existingValidation = Validation::where('model_type', Chauffeur::class)
            ->where('status', 'pending')
            ->where('model_id', $chauffeur->id)
            ->latest('created_at')
            ->first();
            
            if ($existingValidation) {
                return response()->json(['error' => 'Une demande est encore en attente de validation pour ce chauffeur :' . $chauffeur->nom  ], 500);
            }

            $modifier_id = Auth::id(); 
            $input_ = [
                "id" => $chauffeur->id,
                "nom" => $chauffeur->nom,
                "rfid" =>  $chauffeur->rfid,
                "transporteur_id" => $chauffeur->transporteur_id,
                "rfid_physique" => $chauffeur->rfid_physique,
                "contact" => $chauffeur->contact,
                "numero_badge" => $chauffeur->numero_badge,
            ];


            // Créer une demande de suppression dans la table `validations`
            $validation = Validation::create([
                'operator_id' => auth()->id(),
                'model_type' => Chauffeur::class,
                'model_id' => $chauffeur->id,
                'modifications' => $input_ , // Pas de modification, juste une suppression
                'status' => 'pending',
                'action_type' => 'delete',
            ]); 

            $demande_info = Validation::with('operator')->find($validation->id);

            // Envoyer une notification aux administrateurs
            $admins = User::whereHas('roles', function ($query) {
                $query->where('name', 'supper-admin');
            })->get();
        
            Notification::send($admins, new DeleteChauffeurInfoNotification($demande_info->operator->name,$chauffeur->nom));
            
            Alert::success('Succés','Votre demande de suppression a été envoyée.');

            return redirect(route('chauffeurs.index'));
        }catch(Exception $e){
            Alert::error('Erreur',$e->getMessage());
            return redirect(route('chauffeurs.index'));
        }

    }


    // public function delete_sending(Request $request)
    // {
    //     $id = $request->input('chauffeur_id'); // Récupère l'ID envoyé en POST
    //     $chauffeur = $this->chauffeurRepository->find($id);

    //     if (empty($chauffeur)) {
    //         Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));
    //         return redirect(route('chauffeurs.index'));
    //     }

    //     $modifier_id = Auth::id(); 
    //     $input_ = [
    //         "chauffeur_update_type_id" => 4,
    //         "nom" => $chauffeur->nom,
    //         "rfid" =>  $chauffeur->rfid,
    //         "transporteur_id" => $chauffeur->transporteur_id,
    //         "rfid_physique" => $chauffeur->rfid_physique,
    //         "contact" => $chauffeur->contact,
    //         "numero_badge" => $chauffeur->numero_badge,
    //         "chauffeur_id" => $chauffeur->id,
    //         "modifier_id" => $modifier_id,
    //     ];

    //     $chauffeurUpdateStory = ChauffeurUpdateStory::create($input_);


    //     $chauffeur_info = ChauffeurUpdateStory::where('id', $chauffeurUpdateStory->id)
    //         ->with(['chauffeur', 'chauffeur.related_transporteur', 'chauffeur_update_type', 'transporteur', 'modifier'])
    //         ->get()
    //         ->toArray();

    //     // Mail::to("harilovajohnny@gmail.com") // Remplacez par l'email du destinataire
    //     //     ->send(new ChauffeurDeleteMail($chauffeur_info));   

    //     $chauffeur_info_ = ChauffeurUpdateStory::with('modifier')->where('id',$chauffeurUpdateStory->id)->first();
         
    //     // Envoyer une notification aux administrateurs
    //     $admins = User::whereHas('roles', function ($query) {
    //         $query->where('name', 'supper-admin');
    //     })->get();
    
    //     Notification::send($admins, new DeleteChauffeurInfoNotification($chauffeur_info_->modifier->name,$chauffeur_info_->nom));
        
    //     Alert::success('Succés','Votre demande de suppression a été envoyée.');

    //     return redirect(route('chauffeurs.index'));
    // }

}
