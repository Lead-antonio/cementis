<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use App\Models\ChauffeurUpdate;
use App\Models\Validation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidationController extends AppBaseController
{

    public function validateUpdate(Request $request, $validationId)
    {
        $validation = Validation::findOrFail($validationId);

        if (!in_array($request->action, ['approved', 'rejected'])) {
            return response()->json(['message' => 'Action invalide.'], 400);
        }
        
        if ($request->action === 'approved') {
            if (isset($validation->modifications['delete']) && $validation->modifications['delete'] === true) {
                $validation->model->delete();
            } else {
                $validation->model->update($validation->modifications);
            }
        }

        $validation->update(['status' => $request->action, 'admin_id' => auth()->id()]);

        return response()->json(['message' => "Action {$request->action} validée avec succès."]);
    }


    /**
     * Fonction pour la validation des demande de creation , modification et suppression chauffeur 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function ValidationRequestChauffeur(Request $request){
        try{
            $validation_id   =  $request->id;
            $validation = $request->validation;
            $rfid_ = $request->rfid;
            $validator_id = Auth::id(); 
            $validation_value =  Validation::with('admin','model')->find($validation_id);
            
            if($validation == true){
                $rfid_physique = $request->rfidPhysique ?? $validation_value->modifications['rfid_physique'] ?? null;

                $validation_value->update([
                    'status' =>  "approved",
                    'admin_id' =>  $validator_id
                ]);

                if($validation_value->action_type == "update"){
                    ChauffeurUpdate::create([
                        'chauffeur_id' =>  $validation_value->model_id,
                        'rfid'=>  $rfid_,
                        'nom' =>  $validation_value->modifications['nom'],
                        'contact' =>  $validation_value->modifications['contact'],
                        'transporteur_id' =>  $validation_value->modifications['transporteur_id'],
                        'numero_badge'=>  $validation_value->modifications['numero_badge'],
                        'rfid_physique'=>  $validation_value->modifications['rfid_physique'],
                    ]);

                    // mise à jour status de la validation
                    $validation_value->update([
                        'status' =>  "approved",
                        'admin_id' =>  $validator_id
                    ]);

                    $validation_value =  Validation::with('admin','model')->find($validation_id);

                    // Notification création chauffeur vers operateur
                    $message = "Votre demande mise à jour du chauffeur ". $validation_value->model->nom . " a été validée "  . $validation_value->admin->name;
                    $operator_id = $validation_value->operator_id;
                    NotificationValidation($message,$operator_id);

                    return response()->json([
                        'success' => true,
                        'message' => 'La mise à jour a été validée avec succès.'
                    ]);
                }

                if($validation_value->action_type == "delete"){
                    $chauffeur_name = $validation_value->model->nom;
                    $chauffeur_delete = Chauffeur::find($validation_value->model_id);
                    $chauffeur_delete->delete();
                    $validation_value =  Validation::with('admin','model')->find($validation_id);

                    $message = "Votre demande de suppression du chauffeur ". $chauffeur_name . " a été validée par "  . $validation_value->admin->name;
                    $modifier_id = $validation_value->operator_id;
                    // Notification suppression chauffeur vers operateur
                    NotificationValidation($message,$modifier_id);

                    return response()->json([
                        'success' => true,
                        'message' => 'Suppréssion du chauffeur validé'
                    ]);
                }
                
                if($validation_value->action_type == "create"){
                    
                    $chauffeur = Chauffeur::find($validation_value->model_id);
                    $chauffeur->update([
                        'rfid_physique' =>  $rfid_physique,
                        'rfid' =>  $rfid_,
                    ]);

                    $validation_value =  Validation::with('admin')->find($validation_id);

                    // Notification suppression chauffeur vers operateur
                    $message = "Votre demande de création du chauffeur ". $chauffeur->nom . " a été validée par "  . $validation_value->admin->name;
                    $operator_id = $validation_value->operator_id;
                    NotificationValidation($message,$operator_id);

                    return response()->json([
                        'success' => true,
                        'message' => 'Création chauffeur validé'
                    ]);
                }

            }elseif($validation == false){

                $commentaire = $request->commentaire ?? null;
                $chauffeur_name = $validation_value->model->nom;
                
                $validation_value->update([
                    'status' => 'rejected',
                    'admin_id' =>  $validator_id,
                    'commentaire' =>  $commentaire
                ]);
                    
                $type_demande = "mise à jour ";
                
                if($validation_value->action_type == "create"){
                    Chauffeur::where('id', $validation_value->model_id)->delete();
                    $type_demande = "création ";
                }elseif($validation_value->action_type == "delete"){
                    $type_demande = "suppression";
                }elseif($validation_value->action_type == "update"){
                    $type_demande = $validation_value->observation ?? " mise à jour ";
                }

                $validation_value =  Validation::with('admin')->find($validation_id);

                // Notification suppression chauffeur vers operateur
                $message = "Votre demande de " .  $type_demande . " du chauffeur ". $chauffeur_name . " a été refusée par " . $validation_value->admin->name ;
                $operator_id = $validation_value->operator_id;
                NotificationValidation($message,$operator_id);

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


}