<?php

use App\Models\Rotation;
use Carbon\Carbon;
if (!function_exists('fast_trans')) {

    function fast_trans($key, $replace, $default = null)
    {
        $value = __($key, $replace);
        if ($value == $key && $default != null) {
            $value = $default;
        }
        return $value;
    }

}

if (!function_exists('calculerDureeTotale')) {
    function calculerDureeTotale($immatriculation)
    {
        // Récupérer tous les trajets pour le véhicule spécifié
        $trajets = Rotation::where('matricule', $immatriculation)->get();

        // Initialiser la durée totale à zéro
        $dureeTotale = 0;

        // Initialiser les dates de départ et d'arrivée
        $dateDepart = null;
        $dateArrivee = null;

        // Parcourir chaque trajet et calculer la durée totale
        foreach ($trajets as $trajet) {
            $dateHeur = Carbon::parse($trajet->date_heur);

            // Si le trajet est "Départ - Ibity", mettez à jour la date de départ
            if ($trajet->mouvement == 'Depart - Ibity' || $trajet->mouvement == 'Depart - Tamatave') {
                $dateDepart = $dateHeur;
            }

            // Si le trajet est "Arrivée - Ibity", mettez à jour la date d'arrivée et calculez la durée
            if ($trajet->mouvement == 'Arrivée - Ibity' || $trajet->mouvement == 'Arrivée - Tamatave') {
                $dateArrivee = $dateHeur;
                if ($dateDepart !== null) {
                    $dureeTotale += $dateDepart->diffInHours($dateArrivee);
                }
            }
        }

        return $dureeTotale;
    }
}
