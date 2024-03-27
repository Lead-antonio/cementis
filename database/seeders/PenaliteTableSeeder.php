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
            ['id' => 1, 'event' => 'Survitesse', 'point_penalite' => 1],
            ['id' => 2, 'event' => 'Accélération brusque', 'point_penalite' => 1],
            ['id' => 3, 'event' => 'Freinage brusque', 'point_penalite' => 1],
            ['id' => 5, 'event' => 'Temps de conduite continue', 'point_penalite' => 1],
            ['id' => 7, 'event' => 'Temps de conduite maximum dans une journée de travail', 'point_penalite' => 1],
            ['id' => 8, 'event' => 'Temps de repos minimum après une journée de travail', 'point_penalite' => 1],
            ['id' => 9, 'event' => 'Temps de repos hebdomadaire', 'point_penalite' => 1],
       
        ];

        // Insertion des données dans la table "penalite"
        foreach ($data as $row) {
            Penalite::create($row);
        }
    }
    }

