<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
class EventTableSeeder extends Seeder
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
            ['imei' => '359857084872860','chauffeur' => 'Antoine','vehicule' => '9875TBV','type' => 'Survitesse','description' => 'Excès de vitesse','date' => '02-04-2024  08:00:00'],
            ['imei' => '359857084870978','chauffeur' => 'Tojo','vehicule' => '6678TBH','type' => 'Freinage brusque','description' => 'Freinage brusque','date' => '02-04-2024  22:00:00'],
            ['imei' => '359857084870056','chauffeur' => 'Jonathan','vehicule' => '9875TBV','type' => 'Survitesse','description' => 'Excès de vitesse','date' => '04-04-2024  12:00:00'],
            ['imei' => '359857084871234','chauffeur' => 'Benja','vehicule' => '5535TBB','type' => 'Freinage brusque','description' => 'Freinage brusque','date' => '06-04-2024  08:00:00'],
            ['imei' => '359857084875643','chauffeur' => 'Andry','vehicule' => '2134TBC','type' => 'Temps de conduite continue','description' => 'Temps de conduite continue','date' => '09-04-2024  08:00:00'],
            ['imei' => '359857084870978','chauffeur' => 'Tojo','vehicule' => '7650TCA','type' => 'Freinage brusque','description' => 'Freinage brusque','date' => '12-04-2024  01:00:00'],
            ['imei' => '359857084872860','chauffeur' => 'Antoine','vehicule' => '2134TBC','type' => 'Freinage brusque','description' => 'Freinage brusque','date' => '13-04-2024  08:00:00'],
            ['imei' => '359857084875643','chauffeur' => 'Andry','vehicule' => '6678TBH','type' => 'Freinage brusque','description' => 'Freinage brusque','date' => '13-04-2024  12:00:00'],
            ['imei' => '359857084870056','chauffeur' => 'Jonathan','vehicule' => '9875TBV','type' => 'Survitesse','description' => 'Excès de vitesse','date' => '14-04-2024  20:00:00'],
            ['imei' => '359857084871234','chauffeur' => 'Benja','vehicule' => '9561TBA','type' => 'Freinage brusque','description' => 'Freinage brusque','date' => '16-04-2024  20:00:00'], 
            ['imei' => '359857084870056','chauffeur' => 'Jonathan','vehicule' => '6678TBH','type' => 'Accélération brusque','description' => 'Accélération brusque','date' => '20-04-2024  17:00:00'],
            ['imei' => '359857084870056','chauffeur' => 'Jonathan','vehicule' => '6678TBH','type' => 'Freinage brusque','description' => 'Freinage brusque','date' => '21-04-2024  01:00:00'],
            ['imei' => '359857084870978','chauffeur' => 'Tojo','vehicule' => '9875TBV','type' => 'Accélération brusque','description' => 'Accélération brusque','date' => '21-04-2024 13:00:00'],
            ['imei' => '359857084872860','chauffeur' => 'Antoine','vehicule' => '9561TBA','type' => 'Accélération brusque','description' => 'Accélération brusque','date' => '22-04-2024  08:00:00'],
            ['imei' => '359857084875643','chauffeur' => 'Andry','vehicule' => '5535TBB','type' => 'Survitesse','description' => 'Excès de vitesse','date' => '24-04-2024  18:00:00'],
        ];
        

        // Insertion des données dans la table "penalite"
        foreach ($data as $row) {
            Event::create($row);
        }
    }
}