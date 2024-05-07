<?php

namespace App\Repositories;

use App\Models\Infraction;
use App\Repositories\BaseRepository;

/**
 * Class InfractionRepository
 * @package App\Repositories
 * @version May 4, 2024, 8:38 pm CEST
*/

class InfractionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'imei',
        'rfid',
        'vehicule',
        'event',
        'distance',
        'duree_infraction',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'gps_debut',
        'gps_fin',
        'point',
        'duree_initial',
        'insufficance'
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
        return Infraction::class;
    }
}
