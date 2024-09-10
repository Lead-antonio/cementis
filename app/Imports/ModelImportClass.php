<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ModelImportClass implements ToCollection
{
    protected $indexMap;
    protected $modelClass;
    protected $rowCount = 0; // Variable to keep track of row count

    public function __construct($indexMap, $modelClass)
    {
        $this->indexMap = $indexMap; // Associative array mapping fields to column indices
        $this->modelClass = $modelClass; // Model class name as a string
    }

    public function collection(Collection $rows)
    {
        $headers = $rows->shift()->toArray();
        $model = app($this->modelClass); // Create an instance of the model class
        $fillable = $model->getFillable(); // Get fillable fields

        foreach ($rows as $row) {
            $data = [];

            foreach ($this->indexMap as $field => $index) {
                $data[$field] = isset($row[$index]) ? $row[$index] : null;
            }

            // Prepare the data array to only include fillable fields
            $fillableData = array_intersect_key($data, array_flip($fillable));

            // Create the model
            $createdModel = $model->create($fillableData);

            // Specific logic for Personnel model
            // if ($this->modelClass === "\App\Models\Personnel") {
            //     // Create Professionnel and associate it with the newly created Personnel
            //     \App\Models\Professionnel::create([
            //         'personnel_id' => $createdModel->id,
            //     ]);
            // }

            // Increment row count
            $this->rowCount++;
        }
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }
}
