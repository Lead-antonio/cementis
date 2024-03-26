<?php

namespace App\Imports;

use App\Models\ImportExcel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;


class ExcelImportClass implements ToModel
{

    public function model(array $row)
    {
        dd($row[0]);
        return new ImportExcel([
            'name_importation' => "test",
            'rfid_chauffeur' => "test",
            'camion' => $row[0],
            'date_debut' => Carbon::createFromFormat('j/m/Y H:i:s', $row[1])->format('Y-m-d H:i:s'),
            'date_fin' => Carbon::createFromFormat('j/m/Y H:i:s', $row[2])->format('Y-m-d H:i:s'),
            'delais_route' => $row[3],
            'sigdep_reel' => $row[4],
            'marche' => $row[5],
            'adresse_livraison' => $row[6],
            // Map other columns as needed
        ]);
    }

    // /**
    // * @param Collection $collection
    // */
    // public function collection(Collection $collection)
    // {
    //     //
    //     $importedData = [];


        
    //     // foreach ($collection as $row) {
    //     //     $importedData[] = [
    //     //         'camion' => $row[0],
    //     //         'date_debut' => $row[1],
    //     //         'date_fin' => $row[2],
    //     //         'delais_route' => $row[3],
    //     //         'sigdep_reel' => $row[4],
    //     //         'marche' => $row[5],
    //     //         'adresse_livraison' => $row[6],
    //     //     ];
    //     // }
    // }
}
