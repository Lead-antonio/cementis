<?php

namespace App\Repositories;

use App\Models\DataExcel;
use App\Repositories\BaseRepository;

/**
 * Class DataExcelRepository
 * @package App\Repositories
 * @version March 25, 2024, 6:20 pm +07
*/

class DataExcelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'camion',
        'date_debut',
        'date_fin',
        'delais_route',
        'sigdep_reel',
        'marche',
        'adresse_livraison'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DataExcel::class;
    }
}
