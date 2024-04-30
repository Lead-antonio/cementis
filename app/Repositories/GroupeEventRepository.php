<?php

namespace App\Repositories;

use App\Models\GroupeEvent;
use App\Repositories\BaseRepository;

/**
 * Class GroupeEventRepository
 * @package App\Repositories
 * @version April 24, 2024, 2:25 pm CEST
*/

class GroupeEventRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'key',
        'imei',
        'chauffeur',
        'vehicule',
        'type',
        'latitude',
        'longitude',
        'duree'
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
        return GroupeEvent::class;
    }
}
