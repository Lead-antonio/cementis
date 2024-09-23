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
}