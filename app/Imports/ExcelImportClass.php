<?php

namespace App\Imports;

use App\Models\ImportExcel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class ExcelImportClass implements ToCollection
{
    protected $name_file_excel;
    protected $import_calendar_id;

    public function __construct($name_file_excel,$import_calendar_id)
    {
        $this->name_file_excel = $name_file_excel;
        $this->import_calendar_id = $import_calendar_id;
    }

    public function collection(Collection $rows)
    {
        $headers = $rows->shift()->toArray();
        foreach ($rows as $row) {
            $data = [];

            foreach ($row as $index => $value) {
                $header = $headers[$index] ?? null;
                if ($header !== null) {
                    $data[$header] = $value;
                }
            }



            $values = array_values($data);


            ImportExcel::create([
                'name_importation' => $this->name_file_excel,
                'camion' => isset($values[0]) ? $values[0] : null,
                'date_debut' => isset($values[1]) ? $this->convertExcelDateToCarbon($values[1]) : null,
                'date_fin' => isset($values[2]) ? $this->convertExcelDateToCarbon($values[2]) : null,
                'delais_route' => isset($values[3]) ? floatval($values[3]) : null,
                'sigdep_reel' => isset($values[4]) ? $values[4] : null,
                'marche' => isset($values[5]) ? $values[5] : null,
                'adresse_livraison' => isset($values[6]) ? $values[6] : null,
                'import_calendar_id' => $this->import_calendar_id
            ]);
            
        }
    }

    // public function collection(Collection $rows)
    // {
    //     $headers = $rows->shift()->toArray();
    //     $allData = [];

    //     // Récupérer toutes les données dans un tableau
    //     foreach ($rows as $row) {
    //         $values = [];

    //         foreach ($row as $index => $value) {
    //             $header = $headers[$index] ?? null;
    //             if ($header !== null) {
    //                 $values[$header] = $value;
    //             }
    //         }

    //         $data = array_values($values);

    //         // Ajouter les données au tableau
    //         $allData[] = [
    //             'camion' => $data[0] ?? null,
    //             'date_debut' => $this->convertExcelDateToCarbon($data[1] ?? null),
    //             'date_fin' => $this->convertExcelDateToCarbon($data[2] ?? null),
    //             'delais_route' => floatval($data[3] ?? 0),
    //             'sigdep_reel' => $data[4] ?? null,
    //             'marche' => $data[5] ?? null,
    //             'adresse_livraison' => $data[6] ?? null,
    //             'import_calendar_id' => $this->import_calendar_id,
    //         ];
    //     }
        
    //     // Fusionner les données
    //     $mergedEntries = [];
    //     foreach ($allData as $entry) {
    //         $startDate = $entry['date_debut'];
    //         $endDate = $entry['date_fin'];

    //         if (empty($mergedEntries)) {
    //             // Si aucune entrée n'existe, ajouter la première
    //             $mergedEntries[] = $entry;
    //         } else {
    //             // Vérifier si l'entrée chevauche une entrée existante
    //             $lastEntry = &$mergedEntries[count($mergedEntries) - 1];

    //             if ($startDate <= $lastEntry['date_fin']) {
    //                 // Mettre à jour la date de fin si nécessaire
    //                 $lastEntry['date_fin'] = max($lastEntry['date_fin'], $endDate);
    //             } else {
    //                 // Ajouter une nouvelle entrée si pas de chevauchement
    //                 $mergedEntries[] = $entry;
    //             }
    //         }
    //     }

    //     // Enregistrer les données fusionnées dans la base de données
    //     foreach ($mergedEntries as $merged) {
    //         ImportExcel::create([
    //             'name_importation' => $this->name_file_excel,
    //             'camion' => $merged['camion'],
    //             'date_debut' => $merged['date_debut'],
    //             'date_fin' => $merged['date_fin'],
    //             'delais_route' => $merged['delais_route'],
    //             'sigdep_reel' => $merged['sigdep_reel'],
    //             'marche' => $merged['marche'],
    //             'adresse_livraison' => $merged['adresse_livraison'],
    //             'import_calendar_id' => $this->import_calendar_id,
    //         ]);
    //     }
    // }



    private function convertExcelDateToCarbon($excelDate)
    {
        if (!$excelDate) {
            return null;
        }

        $unix_timestamp = Date::excelToTimestamp($excelDate);
        return Carbon::createFromTimestamp($unix_timestamp); 
    }
}
