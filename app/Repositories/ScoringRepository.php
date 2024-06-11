<?php

namespace App\Repositories;

use App\Models\Scoring;
use App\Repositories\BaseRepository;

/**
 * Class ScoringRepository
 * @package App\Repositories
 * @version June 11, 2024, 10:00 am CEST
*/

class ScoringRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_planning',
        'driver_id',
        'transporteur_id',
        'camion',
        'comment',
        'distance',
        'point'
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
        return Scoring::class;
    }
}
