<?php

namespace App\Repositories;

use App\Models\Penalite;
use App\Repositories\BaseRepository;

/**
 * Class PenaliteRepository
 * @package App\Repositories
 * @version March 26, 2024, 5:28 pm +07
*/

class PenaliteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'event',
        'point_penalite'
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
        return Penalite::class;
    }
}
