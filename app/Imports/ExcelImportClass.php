<?php

namespace App\Imports;

use App\Models\ImportExcel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class ExcelImportClass implements ToModel, WithHeadingRow
{
    protected $name_file_excel;
    protected $import_calendar_id;

    public function __construct($name_file_excel,$import_calendar_id)
    {
        $this->name_file_excel = $name_file_excel;
        $this->import_calendar_id = $import_calendar_id;
    }

    public function model(array $row)
    {
        $date_fin = $row['fin'];

        $excel_date = $row['date_debut'];
        $unix_timestamp = Date::excelToTimestamp($excel_date);
        $date_debut = Carbon::createFromTimestamp($unix_timestamp);

        // Vérifier si la valeur de date_fin est vide
        if (empty($date_fin)) {

        }else{

            $excel_date_fin = $row['fin'];
            $unix_timestamp_datefin = Date::excelToTimestamp($excel_date_fin);
            $date_fin = Carbon::createFromTimestamp($unix_timestamp_datefin);
        }

        return new ImportExcel([
            'name_importation' => $this->name_file_excel,
            'rfid_chauffeur' => "Antonio",
            'camion' => $row['camion'],
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'delais_route' => $row['delais_de_route'],
            'sigdep_reel' => $row['sigdep_reel'],
            'marche' => $row['marche'],
            'adresse_livraison' => $row['adresse_de_livraison'],
            'import_calendar_id' => $this->import_calendar_id
        ]);
    }
}
