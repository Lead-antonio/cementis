<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicule;
use App\Models\Transporteur;
class VehiculeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $noms_vehicules = [
            '0435TBU', '0135TCA', '0236TBK', '1096TBU', '6306TBM',
            '3886AJ', '0795TBR', '3885AJ', '0032TAR', '9614ME',
            '5531TBU', '9410TAN', '5487TAP', '1488TCA', '8184TBJ',
            '7184TBL', '4817TBU', '4887TBU', '9388TBU', '2300TBU',
            '3519TAV', '8029TBJ'
        ];
        $transporteurs = \App\Models\Transporteur::pluck('id')->toArray();
        // dd($transporteurs);
        foreach ($noms_vehicules as $nom) {
            Vehicule::create([
                'nom' => $nom,
                'id_transporteur' => $transporteurs[array_rand($transporteurs)],
            ]);
        }

    }
}
