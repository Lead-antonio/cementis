<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chauffeur;
class ChauffeursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Données fictives des chauffeurs avec nom et contact téléphone
      $chauffeurs = [
        [
            'rfid' => 'TABS12',
            'nom' => 'Tojo',
            'contact' => '0349863367',
        ],
        [
            'rfid' => 'PAKS10',
            'nom' => 'Rakoto',
            'contact' => '0340123445',
        ],
        [
            'rfid' => 'KATS12',
            'nom' => 'Rabetrena',
            'contact' => '0348359334',
        ],
        [
            'rfid' => 'TTBS02',
            'nom' => 'Ndrina',
            'contact' => '0323359334',
        ],
        [
            'rfid' => 'XTKS12',
            'nom' => 'Thony',
            'contact' => '0330059334',
        ],
        // Ajoutez d'autres chauffeurs avec nom et contact téléphone si nécessaire
    ];

    // Insérez les chauffeurs dans la base de données
    foreach ($chauffeurs as $chauffeurData) {
        Chauffeur::create($chauffeurData);
    }
}
}
