<?php

namespace App\Repositories;

use App\Models\Movement;
use App\Repositories\BaseRepository;

/**
 * Class MovementRepository
 * @package App\Repositories
 * @version September 9, 2024, 9:59 am CEST
*/

class MovementRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'calendar_id',
        'start_date',
        'start_hour',
        'end_date',
        'end_hour',
        'duration',
        'type'
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
        return Movement::class;
    }
}
