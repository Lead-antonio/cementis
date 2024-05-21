<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transporteur;

class TransporteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transporteurs = [
            ['nom' => 'ZAKATIANA'],
            ['nom' => 'TRANS RAWILSON'],
            ['nom' => 'TRANS TOKY'],
            ['nom' => 'NY ANTSIAKA TRANSPORT'],
            ['nom' => 'SGWT'],
            ['nom' => 'BAD GIRL'],
            ['nom' => 'RZT'],
            ['nom' => 'GICO'],
            ['nom' => 'HIRIDJEE']
        ];

        foreach ($transporteurs as $nom) {
            // Vérifier si le transporteur existe déjà
            if (!Transporteur::where('nom', $nom)->exists()) {
                // Si le transporteur n'existe pas, alors le créer
                Transporteur::create(['nom' => $nom]);
            }
        }
    }
    }

