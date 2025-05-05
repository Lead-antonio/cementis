<?php

namespace App\Repositories;

use App\Models\IncidentVehiculeCoordonnee;
use App\Repositories\BaseRepository;

/**
 * Class IncidentVehiculeCoordonneeRepository
 * @package App\Repositories
 * @version April 30, 2025, 1:27 pm UTC
*/

class IncidentVehiculeCoordonneeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'incident_vehicule_id',
        'latitude',
        'longitude',
        'date_heure',
        'vitesse'
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
        return IncidentVehiculeCoordonnee::class;
    }
}
