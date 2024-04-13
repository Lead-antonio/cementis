<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penalite;
class PenaliteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Données à insérer
        $data = [
            ['id' => 1, 'event' => 'Accélération brusque', 'point_penalite' => 1],
            ['id' => 2, 'event' => 'Survitesse excessive', 'point_penalite' => 1],
            ['id' => 3, 'event' => 'Excès de vitesse en agglomération', 'point_penalite' => 1],
            ['id' => 4, 'event' => 'Excès de vitesse hors agglomération	', 'point_penalite' => 1],
            ['id' => 5, 'event' => 'Freinage brusque', 'point_penalite' => 1],
            ['id' => 6, 'event' => 'Temps de conduite continue jour', 'point_penalite' => 1],
            ['id' => 7, 'event' => 'Temps de conduite continue nuit', 'point_penalite' => 1],
            ['id' => 8, 'event' => 'Temps de conduite maximum dans une journée de travail', 'point_penalite' => 1],
            ['id' => 9, 'event' => 'Temps de pause minimum après conduite continue  jour', 'point_penalite' => 1],
            ['id' => 10, 'event' => 'Temps de pause minimum après conduite continue  nuit', 'point_penalite' => 1],
            ['id' => 11, 'event' => 'Temps de repos minimum après une journée de travail ', 'point_penalite' => 1],
        ];

				


        // Insertion des données dans la table "penalite"
        foreach ($data as $row) {
            Penalite::create($row);
        }
    }
}

