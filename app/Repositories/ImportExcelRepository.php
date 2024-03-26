<?php

namespace App\Repositories;

use App\Models\ImportExcel;
use App\Repositories\BaseRepository;

/**
 * Class ImportExcelRepository
 * @package App\Repositories
 * @version March 26, 2024, 5:14 pm +07
*/

class ImportExcelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_importation',
        'rfid_chauffeur',
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
        return ImportExcel::class;
    }
}
