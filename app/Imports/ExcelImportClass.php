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


            ImportExcel::create([
                'name_importation' => $this->name_file_excel,
                'camion' => array_values($data)[0], 
                'date_debut' => $this->convertExcelDateToCarbon(array_values($data)[1]), 
                'date_fin' => $this->convertExcelDateToCarbon(array_values($data)[2]), 
                'delais_route' => floatval(array_values($data)[3]), 
                'sigdep_reel' => array_values($data)[4], 
                'marche' => array_values($data)[5], 
                'adresse_livraison' => array_values($data)[6], 
                'import_calendar_id' => $this->import_calendar_id
            ]);
            
        }
    }


    private function convertExcelDateToCarbon($excelDate)
    {
        if (!$excelDate) {
            return null;
        }

        $unix_timestamp = Date::excelToTimestamp($excelDate);
        return Carbon::createFromTimestamp($unix_timestamp);
    }
}
