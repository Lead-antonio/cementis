<?php

namespace App\Services;

use App\Models\Validation;

class ValidationService
{
    public static function requestValidation($operatorId, $model, $newData)
    {
        // Comparer les valeurs modifiées
        $modifications = [];
        foreach ($newData as $key => $value) {
            if ($model->$key !== $value) {
                $modifications[$key] = $value;
            }
        }

        if (empty($modifications)) {
            return response()->json(['message' => 'Aucune modification détectée.'], 400);
        }

        return Validation::create([
            'operator_id' => $operatorId,
            'model_type'  => get_class($model),
            'model_id'    => $model->id,
            'modifications' => $modifications,
            'status'      => 'pending',
        ]);
    }


    
}
