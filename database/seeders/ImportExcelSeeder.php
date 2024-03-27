<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImportExcel;
use Faker\Factory as Faker;

class ImportExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['6587TBV', '2024-01-31', '2024-02-04', 2, 'D011', 'TNR', 'IMERINTSIATOSIKA RJ3'],
            ['4744TBL', '2024-01-30', '2024-02-05', 2, 'D011', 'TNR', 'AMBOHIMANDROSO'],
            ['5906TBP', '2024-01-30', '2024-02-03', 2, 'D011', 'TNR', 'TANJOMBATO'],
            ['2052TBJ', '2024-01-31', '2024-02-05', 2, 'D011', 'TNR', 'ARATRA AMBOHIMALAZA'],
            ['7856TBM', '2024-01-31', '2024-02-05', 4, 'D011', 'FNR', 'DEPOT GARE'],
            ['7317TBU', '2024-01-31', '2024-02-04', 2.5, 'D011', 'ABE', 'QIE LIANTSOA BETAFO'],
            ['6366TBM', '2024-01-30', '2024-02-05', 3, 'D011', 'ABS', 'QIE SOASOA RASON AMBOSITRA'],
            ['7748TBM', '2024-02-01', '2024-02-02', 0.25, 'D006', 'FNR', 'Q. RAHERY AMBALAVAO'],
            ['8446TAU', '2024-02-01', '2024-02-01', 0.25, 'D011', 'TAV', 'ADRESSE DE LIVRAISON'],
            ['9999TZV', '2024-02-01', '2024-02-01', 0.25, 'D011', 'TAV', 'ADRESSE DE LIVRAISON'],
            ['8428TBL', '2024-02-02', '2024-02-02', 0.25, 'D001S', 'TNR', 'AMPITATAFIKA'],
            ['0192TBE', '2024-02-02', '2024-02-02', 0.25, 'D001S', 'TNR', 'AMPITATAFIKA'],
            ['7512TAP', '2024-02-02', '2024-02-02', 0.25, 'D001S', 'TNR', 'AMPANGABE - USINE JR METAUX'],
            ['7806TBM', '2024-02-01', '2024-02-02', 0.25, 'D006', 'FNR', 'Q. RAHERY AMBALAVAO'],
            ['7806TBM', '2024-02-01', '2024-02-02', 0.25, 'D006', 'FNR', 'Q. RAHERY AMBALAVAO'],
            ['5706TBL', '2024-02-13', '2024-02-15', 0.25, 'D004', 'ABE', 'DEPOT ABE'],
            ['6590TBK', '2024-02-14', '2024-02-16', 1, 'D004', 'TNR', 'ANTANANARIVO'],
            ['9288TBL', '2024-02-14', '2024-02-16', 2, 'D011', 'TNR', 'ALAROBIA'],
            ['4978TBK', '2024-02-14', '2024-02-16', 2, 'D011', 'TNR', 'PACOM ILAFY'],
            ['4028TBU', '2024-02-14', '2024-02-19', 2, 'D011', 'TNR', 'AMBATOMIRAHAVAVY'],
            ['4676TBM', '2024-02-14', '2024-02-15', 0.25, 'D011', 'TAV', 'Q.GRACE TANAMBAO V']
        
            // Ajoutez ici les autres données d'importation
        ];

        $faker = Faker::create();

        foreach ($data as $row) {
            ImportExcel::create([
                'name_importation' => $faker->unique()->word, // Générer une valeur aléatoire pour name_importation
                'rfid_chauffeur' => $faker->unique()->word,
                'camion' => $row[0],
                'date_debut' => $row[1],
                'date_fin' => $row[2],
                'delais_route' => $row[3],
                'sigdep_reel' => $row[4],
                'marche' => $row[5],
                'adresse_livraison' => $row[6],
            ]);
        }
    }
}
