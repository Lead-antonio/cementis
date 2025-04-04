<?php

namespace App\Http\Controllers;

use App\DataTables\ChauffeurUpdateStoryDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateChauffeurUpdateStoryRequest;
use App\Http\Requests\UpdateChauffeurUpdateStoryRequest;
use App\Repositories\ChauffeurUpdateStoryRepository;
use Exception;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Mail\ChauffeurUpdateMail;
use App\Models\Chauffeur;
use App\Models\ChauffeurUpdate;
use App\Models\ChauffeurUpdateStory;
use App\Models\ChauffeurUpdateType;
use App\Models\Transporteur;
use App\Models\User;
use App\Models\Validation;
use App\Notifications\UpdateChauffeurInfoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;


use Response;

class ChauffeurUpdateStoryController extends AppBaseController
{
    /** @var ChauffeurUpdateStoryRepository $chauffeurUpdateStoryRepository*/
    private $chauffeurUpdateStoryRepository;

    public function __construct(ChauffeurUpdateStoryRepository $chauffeurUpdateStoryRepo)
    {
        $this->chauffeurUpdateStoryRepository = $chauffeurUpdateStoryRepo;
    }

    /**
     * Display a listing of the ChauffeurUpdateStory.
     *
     * @param ChauffeurUpdateStoryDataTable $chauffeurUpdateStoryDataTable
     *
     * @return Response
     */
    public function index(ChauffeurUpdateStoryDataTable $chauffeurUpdateStoryDataTable)
    {
        return $chauffeurUpdateStoryDataTable->render('chauffeur_update_stories.index');
    }


    /**
     * Show the form for creating a new ChauffeurUpdateStory.
     *
     * @return Response
     */
    public function create()
    {
        return view('chauffeur_update_stories.create');
    }


    /**
     * Store a newly created ChauffeurUpdateStory in storage.
     *
     * @param CreateChauffeurUpdateStoryRequest $request
     *
     * @return Response
     */
    public function store(CreateChauffeurUpdateStoryRequest $request)
    {
        $input = $request->all();

        $modifier_id = Auth::id(); 
        $chauffeur = Chauffeur::find($request->chauffeur_id);

        $validation = Validation::where('model_id',$chauffeur->id)
                        ->where('model_type',Chauffeur::class)->where('status','pending')
                        ->latest('created_at')->first();
        
        if($validation){
            Alert::warning('Attention', 'Une demande est encore en attente de validation pour ce chauffeur');
            return redirect()->back();
        }
        
        $tranporteur = Transporteur::find($request->hidden_transporteur_id);
        $modifications = [
            "nom" => $request->nom,
            "rfid" =>  $request->rfid,
            "transporteur_id" => $request->hidden_transporteur_id,
            "rfid_physique" => $request->rfid_physique,
            "contact" => $request->contact,
            "numero_badge" => $request->numero_badge,
            "transporteur" => $tranporteur->nom
        ];

        $update_type = ChauffeurUpdateType::find( $request->chauffeur_update_type_id);
        // Stocker la demande de validation
        Validation::create([
            'operator_id' => auth()->id(),
            'model_type' => Chauffeur::class,
            'model_id' => $chauffeur->id,
            'modifications' => $modifications,
            'status' => 'pending',
            'action_type' => 'update',
            'observation' => $update_type->name,
        ]);
    
        // $chauffeur_info = ChauffeurUpdateStory::where('id',$chauffeurUpdateStory->id)
        // ->with(['chauffeur','chauffeur.related_transporteur','chauffeur_update_type','transporteur'])->get()->toArray();  

          // Envoyer une notification aux administrateurs
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'supper-admin');
        })->get();

        $modifier = User::find(auth()->id());
        
        Notification::send($admins, new UpdateChauffeurInfoNotification($modifier->name,$chauffeur->nom));
        Alert::success('Succés','Votre demande de mise à jour a été envoyée!');
        
        return redirect(route('chauffeurs.index'));
    }

    /**
     * Display the specified ChauffeurUpdateStory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->find($id);

        if (empty($chauffeurUpdateStory)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateStories.singular')]));

            return redirect(route('chauffeurUpdateStories.index'));
        }

        return view('chauffeur_update_stories.show')->with('chauffeurUpdateStory', $chauffeurUpdateStory);
    }

    /**
     * Show the form for editing the specified ChauffeurUpdateStory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->find($id);
        
        if (empty($chauffeurUpdateStory)) { 
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateStories.singular')]));

            return redirect(route('chauffeurUpdateStories.index'));
        }

        return view('chauffeur_update_stories.edit')->with('chauffeurUpdateStory', $chauffeurUpdateStory);
    }

    /**
     * Update the specified ChauffeurUpdateStory in storage.
     *
     * @param int $id
     * @param UpdateChauffeurUpdateStoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChauffeurUpdateStoryRequest $request)
    {
        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->find($id);

        if (empty($chauffeurUpdateStory)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateStories.singular')]));

            return redirect(route('chauffeurUpdateStories.index'));
        }

        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/chauffeurUpdateStories.singular')]));

        return redirect(route('chauffeurUpdateStories.index'));
    }

    /**
     * Remove the specified ChauffeurUpdateStory from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->find($id);

        if (empty($chauffeurUpdateStory)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateStories.singular')]));

            return redirect(route('chauffeurUpdateStories.index'));
        }

        $this->chauffeurUpdateStoryRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/chauffeurUpdateStories.singular')]));

        return redirect(route('chauffeurUpdateStories.index'));
    }


    /**
     * Summary of ValidationUpdateChauffeur
     * jonny
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function ValidationUpdateChauffeur(Request $request){
        try{
            
            $chauffeur_update_id =  $request->id;
            $validation = $request->validation;
            $rfid_ = $request->rfid;
            $validator_id = Auth::id(); 
            $chauffeur_update =  ChauffeurUpdateStory::find($chauffeur_update_id);
            $rfid_physique = $request->rfidPhysique ??  $chauffeur_update->rfid_physique;

            if($validation == true){
                
                $chauffeur_update =  ChauffeurUpdateStory::find($chauffeur_update_id);

                $chauffeur_update->update([
                    'validation' =>  ChauffeurUpdateStory::VALIDATION_VALIDEE,
                    'validator_id' =>  $validator_id
                ]);

                if($chauffeur_update->chauffeur_update_type_id < 4){
                    if($rfid_ == null){
                        $rfid_ =  $chauffeur_update->rfid;
                    }
                    
                    ChauffeurUpdate::create([
                        'chauffeur_id' =>  $chauffeur_update->chauffeur_id,
                        'rfid'=>  $rfid_,
                        'nom' =>  $chauffeur_update->nom,
                        'contact' =>  $chauffeur_update->contact,
                        'transporteur_id' =>  $chauffeur_update->transporteur_id,
                        'numero_badge'=>  $chauffeur_update->numero_badge,
                        'rfid_physique'=>  $chauffeur_update->rfid_physique,
                    ]);

                    // Notification création chauffeur vers operateur
                    $message = "Votre demande mise à jour du chauffeur ". $chauffeur_update->nom . " a été validée "  . $chauffeur_update->validator->name;
                    $modifier_id = $chauffeur_update->modifier_id;
                    NotificationValidation($message,$modifier_id);

                    return response()->json([
                        'success' => true,
                        'message' => 'La mise à jour a été validée avec succès.'
                    ]);
                }

                if($chauffeur_update->chauffeur_update_type_id == 4){
                    $chauffeur_delete = Chauffeur::find($chauffeur_update->chauffeur_id);
                    $chauffeur_delete->delete();

                    $message = "Votre demande de suppression du chauffeur ". $chauffeur_update->nom . " a été validée "  . $chauffeur_update->validator->name;
                    $modifier_id = $chauffeur_update->modifier_id;
                    // Notification suppression chauffeur vers operateur
                    NotificationValidation($message,$modifier_id);

                    return response()->json([
                        'success' => true,
                        'message' => 'Suppréssion du chauffeur validé'
                    ]);
                }

                if($chauffeur_update->chauffeur_update_type_id == 5){

                    $chauffeur_update->update([
                        'validation' =>  ChauffeurUpdateStory::VALIDATION_VALIDEE,
                        'validator_id' =>  $validator_id,
                        'rfid_physique' =>  $rfid_physique,
                        'rfid' =>  $rfid_,
                    ]);

                    Chauffeur::create([
                        'chauffeur_id' =>  $chauffeur_update->chauffeur_id,
                        'rfid'=>  $rfid_,
                        'nom' =>  $chauffeur_update->nom,
                        'contact' =>  $chauffeur_update->contact,
                        'transporteur_id' =>  $chauffeur_update->transporteur_id,
                        'numero_badge'=>  $chauffeur_update->numero_badge,
                        'rfid_physique'=> $rfid_physique,
                    ]);

                    // Notification suppression chauffeur vers operateur
                    $message = "Votre demande de création du chauffeur ". $chauffeur_update->nom . " a été validée par "  . $chauffeur_update->validator->name;
                    $modifier_id = $chauffeur_update->modifier_id;
                    NotificationValidation($message,$modifier_id);

                    return response()->json([
                        'success' => true,
                        'message' => 'Création chauffeur validé'
                    ]);
                }

            }elseif($validation == false){

                $commentaire = $request->commentaire ?? null;
                
                $chauffeur_update->update([
                    'validation' => ChauffeurUpdateStory::VALIDATION_REFUSEE,
                    'validator_id' =>  $validator_id,
                    'commentaire' =>  $commentaire
                ]);

                $type_demande = "mise à jour ";
                if($chauffeur_update->chauffeur_update_type_id == 5){
                    $type_demande = "création ";
                }elseif($chauffeur_update->chauffeur_update_type_id == 4){
                    $type_demande = "suppression";
                }

                // Notification suppression chauffeur vers operateur
                $message = "Votre demande de " .  $type_demande . " du chauffeur ". $chauffeur_update->nom . "a été refusée par " . $chauffeur_update->validator->name ;
                $modifier_id = $chauffeur_update->modifier_id;
                NotificationValidation($message,$modifier_id);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Refus validé'
                ]);
                
            }
        }catch(Exception $e){
            return response()->json([
                'error' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Affichage de la liste des mise à jour chauffeur à valider 
     * jonny
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    // public function validation_list(){
    //     $chauffeur_update = ChauffeurUpdateStory::with(['chauffeur','chauffeur.related_transporteur','chauffeur_update_type','transporteur','validator','modifier'])
    //     ->orderBy('id',"desc")
    //     ->paginate(5);

    //     return view('chauffeur_update_stories.validation_list',compact('chauffeur_update'));
    // }


    /**
     * /jonny
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function validation_list()
    {
        $validations = Validation::with([ 'model' => function ($query) {
            // Vérifier si le modèle est un Chauffeur ou un Véhicule et charger les relations correspondantes
            $query->with('related_transporteur');
        },
        'operator', 'admin']) // Joindre les informations nécessaires
            // ->where('status', 'pending') // Seulement les validations en attente
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Pagination

        return view('chauffeur_update_stories.validation_list', compact('validations'));
    }


    

}
