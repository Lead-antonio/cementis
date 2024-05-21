<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transporteur;
use App\Models\Chauffeur;
use App\Models\Vehicule;

class ChauffeurTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transporters = [
            'BAD GIRL' => [
                'vehicles' => [' 0135TCA', '0435TBU', '3235TBS'],
                'chauffeurs' => [
                    ['nom' => 'NAZIRA TSITINIA OLIVIER', 'rfid' => '3A00D6DAEC'],
                    ['nom' => 'RAJOMALAHY ROLLAND', 'rfid' => '3A00D6859E'],
                    ['nom' => 'RANDRIAMANAMPISOA JEAN FREDDY MICHEL', 'rfid' => '3B00FA4CDB']
                ]
            ],
            'BIO TRANS'  => [
                'vehicles'  => ['0236TBK', '1096TBU', '6306TBM'],
                'chauffeurs'  => [
                    ['nom'  => 'RAFARALAHY THEOPHILE', 'rfid'  => '3B00F8F86B'],
                    ['nom'  => 'RAVELONAHINA Serge Richard', 'rfid'  => '3B00F9D740'],
                    ['nom'  => 'ZANOELITOVO Erick Martial', 'rfid'  => '3A00D5D6D5']
                ]
            ],
            'GICO'  => [
                'vehicles'  => ['0795TBR', '3885AJ', '3886AJ'],
                'chauffeurs'  => [
                    ['nom'  => 'NIRINAMANANA Mamy Tianasoa Claude', 'rfid'  => '3A00D5F69D'],
                    ['nom'  => 'RAHAMBISON Andriamiarintsoa Jean', 'rfid'  => '3A00E817C9'],
                    ['nom'  => 'RANDRIANANTENAINA Jean de Dieu', 'rfid'  => '3A00D6AF1C']
                ]
            ],
            'HIRIDJEE'  => [
                'vehicles'  => ['0032TAR', '7511TAP', '7512TAP'],
                'chauffeurs'  => [
                    ['nom'  => 'ANDRIANJOELIMBELOSON ONJA', 'rfid'  => '3A00E83B43'],
                    ['nom'  => 'RANDRIANIRINA TOKINIAINA JERISOA', 'rfid'  => '3A00E6EC1F'],
                    ['nom'  => 'RASOLONJATOVO Tsilavina Gilbert', 'rfid'  => '3A00D6A478']
                ]
            ],
            'MAMYTIANA'  => [
                'vehicles'  => ['0103MF', '5531TBU', '9614ME'],
                'chauffeurs'  => [
                    ['nom'  => 'FANOMEZANTSOA ROLLAND', 'rfid'  => '360021B4A1'],
                    ['nom'  => 'RANDRIAHONENANTSOA RINA JEAN MICHEL', 'rfid'  => '2C00DA184B'],
                    ['nom'  => 'RANDRIANJAFY RIJANIAINA OLIVIER GABRIEL', 'rfid'  => '3A00E7E1F8']
                ]
            ],
            'NY ANTSIAKA TRANSPORT'  => [
                'vehicles'  => ['5487TAP', '9410TAN'],
                'chauffeurs'  => [
                    ['nom'  => 'ANDRIANJATOMANANA Odéam Heriniaina', 'rfid'  => '3D0049B48E'],
                    ['nom'  => 'RANDRIAMBOAVONJY Nokasaina Lalao Franck', 'rfid'  => '3B00FA9EEE']
                ]
            ],
            'RAOILIARISON'  => [
                'vehicles'  => ['1488TCA'],
                'chauffeurs'  => [
                    ['nom'  => 'RAFANOMEZANTSOA Franck Armand', 'rfid'  => '3A00D5A7BE']
                ]
            ],
            'RZT'  => [
                'vehicles'  => ['7184TBL', '8184TBJ'],
                'chauffeurs'  => [
                    ['nom'  => 'HERY TINA MICHEL PATRICK', 'rfid'  => '3B00F903D4'],
                    ['nom'  => 'Randriamala Odon Arsene', 'rfid'  => '3A00D5FF9D']
                ]
            ],
            'SGWT'  => [
                'vehicles'  => ['4817TBU', '4887TBU'],
                'chauffeurs'  => [
                    ['nom'  => 'RANDRIAMALALA Avotriniaina', 'rfid'  => '3A00D636BC'],
                    ['nom'  => 'RANDRIAMIHARISOA Elia Xavier', 'rfid'  => '3B00F91BD1']
                ]
            ],
            'TRANS RAWILSON'  => [
                'vehicles'  => ['9388TBU'],
                'chauffeurs'  => [
                    ['nom'  => 'RAZANADRAKOTO VONJINIAINA', 'rfid'  => '3B00F8E44D']
                ]
            ],
            'TRANS TOKY'  => [
                'vehicles'  => ['2300TBU', '3519TAV'],
                'chauffeurs'  => [
                    ['nom'  => 'ANDRIAMIHAJARISON TOKY', 'rfid'  => '3B00F8EA95'],
                    ['nom'  => 'RAZAKARANDAHY Felanamontana Dina', 'rfid'  => '3B00F99049']
                ]
            ],
            'ZAKATIANA'  => [
                'vehicles'  => ['8029TBJ'],
                'chauffeurs'  => [
                    ['nom'  => 'RAHAJANIAINA FRANCOIS', 'rfid'  => '3B00F9BCD9']
                ]
            ]
        ];

        // Loop through each transporter and create the transporter, their vehicles, and their chauffeurs
        foreach ($transporters as $transporterName  => $data) {
            // Create or get the transporter
            $transporter = Transporteur::firstOrCreate(['nom'  => $transporterName]);

            // Create vehicles for this transporter
            foreach ($data['vehicles'] as $vehicleName) {
                Vehicule::create([
                    'nom'  => $vehicleName,
                    'id_transporteur'  => $transporter->id,
                ]);
            }

            // Create chauffeurs for this transporter
            foreach ($data['chauffeurs'] as $chauffeurData) {
                Chauffeur::create([
                    'nom'  => $chauffeurData['nom'],
                    'rfid'  => $chauffeurData['rfid'],
                    'transporteur_id'  => $transporter->id,
                ]);
            }
        }
    }
}
