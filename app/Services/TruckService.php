<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class TruckService
{
    /**
     * Antonio
     * Retourne l'imatriculation d'un camion à partir du code imei.
     */
    public function getTruckPlateNumberByImei($imei){
        try {
            // Récupération du point de pénalité depuis la base de données
            $result = DB::table('vehicule')
                ->select('nom')
                ->where('imei', '=', $imei)
                ->first();

            // Gestion du cas où aucun point de pénalité n'est trouvé
            return $result ? $result->nom : ""; // Retourne 0 si pas de pénalité trouvée

        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error("Erreur lors de la récupération de l'imatriculation : " . $e->getMessage());
            return 0;
        }
    }


    public function  updateVehiculeData(){
        $old_vehicule = DB::table('vehicule as v1')
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('vehicule as v2')
                ->whereRaw('v1.imei = v2.imei')
                ->whereRaw('v1.id <> v2.id')
                ->whereRaw('v1.id < v2.id'); // Ancien chauffeur
        })
        ->get();
        
        $ids_to_delete = [];

        foreach ($old_vehicule as $old) {
            // Récupérer tous les chauffeurs récents liés à ce RFID
            $new_vehicule = DB::table('vehicule')
                ->where('imei', $old->imei)
                ->where('id', '>', $old->id) // Récupérer les nouveaux
                ->get();
            

            foreach ($new_vehicule as $new) {
                DB::table('vehicule_updates')->insert([
                    'vehicule_id'        => $old->id,  // ID du chauffeur ancien
                    'id_transporteur' => $new->id_transporteur,
                    'imei'             => $new->imei,
                    'nom'              => $new->nom,
                    'date_installation' => $new->created_at,
                    'created_at' => $new->created_at,
                    'updated_at' => $new->updated_at,
                ]);

                $ids_a_supprimer[] = $new->id;
            }

            if (!empty($ids_a_supprimer)) {
                DB::table('installation')
                    ->whereIn('vehicule_id', $ids_a_supprimer)
                    ->delete();
                DB::table('vehicule')
                    ->whereIn('id', $ids_a_supprimer)
                    ->delete();
            }
        }
    }
}