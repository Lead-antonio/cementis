<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Chauffeur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DriverImportClass  implements ToModel, WithHeadingRow
{
    public function __construct(){}

    public function model(array $row)
    {
        if(isset($row['id_rfid_plateforme']) && isset($row['nom_conducteur'])){
            return new Chauffeur([
                'rfid' => $row['id_rfid_plateforme'],
                'nom' => $row['nom_conducteur'],
            ]);
        }
    }
}
