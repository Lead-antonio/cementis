<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Movement;

class MovementService
{
    /**
     * Antonio
     * Retourne les mouvements d'un calendrier donné.
     */
    public function getAllMouvementDuringCalendar($calendar_id){
        try {
            // Récupération de mouvements effectuer durant le calendrier
            $movements = Movement::where('calendar_id', $calendar_id)->get();

            // Gestion du cas où aucun point de pénalité n'est trouvé
            return $movements ? $movements : []; // Retourne 0 si pas de pénalité trouvée

        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error("Erreur lors de la récupération des mouvements pendant un calandrier : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Antonio
     * Retourne les mouvements d'un calendrier donné.
     */
    public function getStopBehindGivingDateAndHour($movements, $targetDate, $targetTime){
        try {
            $closestStop = $movements->filter(function ($movement) use ($targetDate, $targetTime) {
                return $movement->type === 'STOP' && $movement->start_date === $targetDate && (
                    ($movement->start_hour >= $targetTime)
                );
            })
            ->sortBy(function ($movement) {
                return $movement->start_date . ' ' . $movement->start_hour;
            })
            ->first();
        
            return $closestStop;

        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error("Erreur lors de la récupération des mouvements pendant un calandrier : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Antonio
     * Trier les mouvements par couple DRIVE + STOP.
     */
    public function organizeMovements($allmovements){
        try {
            // Trier les mouvements par start_date et start_hour
            $sortedMovements = $allmovements->sortBy(function($movement) {
                return $movement->start_date . ' ' . $movement->start_hour;
            })->values()->toArray(); // Convertir en tableau après tri

            $organizedMovements = [];
            $previousMovement = null;

            foreach ($sortedMovements as $currentMovement) {
                // Si c'est le premier mouvement, on l'ajoute directement
                if (!$previousMovement) {
                    $organizedMovements[] = $currentMovement;
                } else {
                    // Si le précédent mouvement est un DRIVE et que le courant est un STOP qui suit immédiatement
                    if ($previousMovement['type'] === 'DRIVE' && $currentMovement['type'] === 'STOP' &&
                        $previousMovement['end_date'] === $currentMovement['start_date'] &&
                        $previousMovement['end_hour'] === $currentMovement['start_hour']) {
                        // Ajouter le STOP juste après le DRIVE
                        $organizedMovements[] = $currentMovement;
                    } else {
                        // Si ce n'est pas le cas, ajouter le mouvement actuel
                        $organizedMovements[] = $currentMovement;
                    }
                }

                // Mettre à jour le mouvement précédent
                $previousMovement = $currentMovement;
            }

            return $organizedMovements;

        } catch (Exception $e) {
            // Gestion des erreurs
            Log::error("Erreur lors du trie des mouvements par couple DRIVE + STOP : " . $e->getMessage());
            return 0;
        }
    }
}