<?php

namespace App\Repositories;

use App\Models\Vehicule;
use App\Repositories\BaseRepository;

/**
 * Class VehiculeRepository
 * @package App\Repositories
 * @version May 7, 2024, 10:10 am CEST
*/

class VehiculeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'nom',
        'vehicule_update.nom'
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
        return Vehicule::class;
    }
}
