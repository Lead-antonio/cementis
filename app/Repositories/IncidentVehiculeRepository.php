<?php

namespace App\Repositories;

use App\Models\IncidentVehicule;
use App\Repositories\BaseRepository;

/**
 * Class IncidentVehiculeRepository
 * @package App\Repositories
 * @version April 30, 2025, 1:23 pm UTC
*/

class IncidentVehiculeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'test'
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
        return IncidentVehicule::class;
    }
}
