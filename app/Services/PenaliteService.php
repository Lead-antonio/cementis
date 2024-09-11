<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class PenaliteService
{
    /**
     * Retourne le point de pénalité pour un type d'événement donné.
     */
    public function getPointPenaliteByEventType($event){
        try {
            $eventType = trim($event);

            // Récupération du point de pénalité depuis la base de données
            $result = DB::table('penalite')
                ->select('point_penalite')
                ->where('event', '=', $eventType)
                ->first();

            // Gestion du cas où aucun point de pénalité n'est trouvé
            return $result ? $result->point_penalite : 0; // Retourne 0 si pas de pénalité trouvée

        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error("Erreur lors de la récupération du point de pénalité pour l'événement $event: " . $e->getMessage());
            return 0;
        }
    }
}