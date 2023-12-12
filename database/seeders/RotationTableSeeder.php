<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class RotationTableSeeder extends Seeder
{
    public function run()
    {
        // $mouvements = ['entrée', 'sortie'];

        // $places = [
        //     'Antananarivo' => ['-18.8792', '47.5079'],
        //     'Morondava' => ['-20.2833', '44.2833'],
        //     'Toamasina' => ['-18.1494', '49.4029'],
        //     'Mahajanga' => ['-15.7167', '46.3167'],
        //     'Toliara' => ['-23.3505', '43.6634'],
        //     'Antsirabe' => ['-19.874', '47.0333'],
        //     'Fianarantsoa' => ['-21.4373', '47.0925'],
        //     'Antsohihy' => ['-14.8791', '47.9875'],
        //     'Ibity' => ['-19.6299', '47.3159'], 
        //     'Antalaha' => ['-14.9006', '50.2785'], 
        //     'Maevatanana' => ['-16.9869', '46.3236'],
        //     // Ajoutez d'autres noms de lieu avec leurs coordonnées ici
        // ];

        // $matriculeCounter = 3988; // Définir la valeur de départ pour le matricule

        // foreach (range(1, 11) as $index) {
        //     $origin = array_rand($places);
        //     $destination = array_rand($places);
        //     $date_heur = Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 24))->format('Y-m-d H:i:s');

        //     DB::table('rotation')->insert([
        //         'matricule' => $matriculeCounter . 'AH',
        //         'mouvement' => $mouvements[array_rand($mouvements)],
        //         'date_heur' => $date_heur,
        //         'adresse' => "Ibity",
        //         'coordonne_gps' => "{$origin} - {$destination}",
        //     ]);

        //     $matriculeCounter++; // Incrémenter le matricule
        // }

        $data = [
            ['id' => 1, 'matricule' => '3993AH', 'mouvement' => 'Depart - Ibity', 'date_heur' => '2023-11-11 07:14:05', 'coordonne_gps' => 'Ibity - Fianarantsoa', 'adresse' => 'Ibity', 'tranche' => '1'],
            ['id' => 2, 'matricule' => '3993AH', 'mouvement' => 'Usine - Arrivée', 'date_heur' => '2023-11-11 09:57:57', 'coordonne_gps' => 'Maevatanana - Maevatanana', 'adresse' => 'Ibity', 'tranche' => '1'],
            ['id' => 3, 'matricule' => '3993AH', 'mouvement' => 'Usine - Départ', 'date_heur' => '2023-11-13 09:58:05', 'coordonne_gps' => 'Antalaha - Antananarivo', 'adresse' => 'Ibity', 'tranche' => '1'],
            ['id' => 4, 'matricule' => '3993AH', 'mouvement' => 'Antananarivo - Arrivée', 'date_heur' => '2023-11-13 16:13:05', 'coordonne_gps' => 'Antsirabe - Fianarantsoa', 'adresse' => 'Ibity', 'tranche' => '2'],
            ['id' => 5, 'matricule' => '3993AH', 'mouvement' => 'RN7 vers Antananarivo', 'date_heur' => '2023-11-14 04:13:05', 'coordonne_gps' => 'Maevatanana - Mahajanga', 'adresse' => 'Ibity', 'tranche' => '2'],
            ['id' => 6, 'matricule' => '3993AH', 'mouvement' => 'Antananarivo - Client', 'date_heur' => '2023-11-14 06:13:05', 'coordonne_gps' => 'Toamasina - Toliara', 'adresse' => 'Ibity', 'tranche' => '2'],
            ['id' => 7, 'matricule' => '3993AH', 'mouvement' => 'Antananarivo - Garage', 'date_heur' => '2023-11-14 07:13:05', 'coordonne_gps' => 'Toamasina - Antsohihy', 'adresse' => 'Ibity', 'tranche' => '2'],
            ['id' => 8, 'matricule' => '3993AH', 'mouvement' => 'Antananarivo - Dépôt', 'date_heur' => '2023-11-14 09:13:05', 'coordonne_gps' => 'Antalaha - Morondava', 'adresse' => 'Ibity', 'tranche' => '2'],
            ['id' => 9, 'matricule' => '3993AH', 'mouvement' => 'Antananarivo - Départ', 'date_heur' => '2023-11-14 17:13:05', 'coordonne_gps' => 'Antalaha - Antalaha', 'adresse' => 'Ibity', 'tranche' => '2'],
            ['id' => 10, 'matricule' => '3993AH', 'mouvement' => 'Arrivée - Ibity', 'date_heur' => '2023-11-15 03:13:05', 'coordonne_gps' => 'Antalaha - Antsirabe', 'adresse' => 'Ibity', 'tranche' => '3'],

            ['id' => 11, 'matricule' => '9345TK', 'mouvement' => 'Depart - Tamatave', 'date_heur' => '2023-11-11 04:00:00', 'coordonne_gps' => 'Ibity - Fianarantsoa', 'adresse' => 'Tamatave', 'tranche' => '1'],
            ['id' => 13, 'matricule' => '9345TK', 'mouvement' => 'Usine - Départ', 'date_heur' => '2023-11-11 05:00:05', 'coordonne_gps' => 'Antalaha - Antananarivo', 'adresse' => 'Tamatave', 'tranche' => '1'],
            ['id' => 12, 'matricule' => '9345TK', 'mouvement' => 'Usine - Arrivée', 'date_heur' => '2023-11-11 05:30:00', 'coordonne_gps' => 'Maevatanana - Maevatanana', 'adresse' => 'Tamatave', 'tranche' => '1'],
            ['id' => 14, 'matricule' => '9345TK', 'mouvement' => 'Antananarivo - Arrivée', 'date_heur' => '2023-11-11 20:30:05', 'coordonne_gps' => 'Antsirabe - Fianarantsoa', 'adresse' => 'Tana', 'tranche' => '2'],
            ['id' => 15, 'matricule' => '9345TK', 'mouvement' => 'RN2 vers Antananarivo', 'date_heur' => '2023-11-11 20:59:05', 'coordonne_gps' => 'Maevatanana - Mahajanga', 'adresse' => 'Tana', 'tranche' => '2'],
            ['id' => 16, 'matricule' => '9345TK', 'mouvement' => 'Antananarivo - Client', 'date_heur' => '2023-11-12 08:00:00', 'coordonne_gps' => 'Toamasina - Toliara', 'adresse' => 'Tana', 'tranche' => '2'],
            ['id' => 17, 'matricule' => '9345TK', 'mouvement' => 'Antananarivo - Garage', 'date_heur' => '2023-11-12 09:30:05', 'coordonne_gps' => 'Toamasina - Antsohihy', 'adresse' => 'Tana', 'tranche' => '2'],
            ['id' => 18, 'matricule' => '9345TK', 'mouvement' => 'Antananarivo - Dépôt', 'date_heur' => '2023-11-12 10:15:05', 'coordonne_gps' => 'Antalaha - Morondava', 'adresse' => 'Tana', 'tranche' => '2'],
            ['id' => 19, 'matricule' => '9345TK', 'mouvement' => 'Antananarivo - Départ', 'date_heur' => '2023-11-12 16:13:05', 'coordonne_gps' => 'Antalaha - Antalaha', 'adresse' => 'Tana', 'tranche' => '2'],
            ['id' => 20, 'matricule' => '9345TK', 'mouvement' => 'Arrivée - Tamatave', 'date_heur' => '2023-11-12 23:13:05', 'coordonne_gps' => 'Antalaha - Antsirabe', 'adresse' => 'Tamatave', 'tranche' => '3'],
        ];

        DB::table('rotation')->insert($data);
    }
}
