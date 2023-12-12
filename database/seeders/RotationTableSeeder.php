<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class RotationTableSeeder extends Seeder
{
    public function run()
    {
        $mouvements = ['entrée', 'sortie'];

        $places = [
            'Antananarivo' => ['-18.8792', '47.5079'],
            'Morondava' => ['-20.2833', '44.2833'],
            'Toamasina' => ['-18.1494', '49.4029'],
            'Mahajanga' => ['-15.7167', '46.3167'],
            'Toliara' => ['-23.3505', '43.6634'],
            'Antsirabe' => ['-19.874', '47.0333'],
            'Fianarantsoa' => ['-21.4373', '47.0925'],
            'Antsohihy' => ['-14.8791', '47.9875'],
            'Ibity' => ['-19.6299', '47.3159'], 
            'Antalaha' => ['-14.9006', '50.2785'], 
            'Maevatanana' => ['-16.9869', '46.3236'],
            // Ajoutez d'autres noms de lieu avec leurs coordonnées ici
        ];

        $matriculeCounter = 3988; // Définir la valeur de départ pour le matricule

        foreach (range(1, 33) as $index) {
            $origin = array_rand($places);
            $destination = array_rand($places);
            $date_heur = Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 24))->format('Y-m-d H:i:s');

            DB::table('rotation')->insert([
                'matricule' => $matriculeCounter . 'AH',
                'mouvement' => $mouvements[array_rand($mouvements)],
                'date_heur' => $date_heur,
                'coordonne_gps' => "{$origin} - {$destination}",
            ]);

            $matriculeCounter++; // Incrémenter le matricule
        }
    }
}
