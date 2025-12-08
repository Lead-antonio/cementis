<?php

namespace App\Repositories;

use App\Models\ScoreDriver;
use App\Repositories\BaseRepository;

/**
 * Class ScoreDriverRepository
 * @package App\Repositories
 * @version November 11, 2025, 6:16 am UTC
*/

class ScoreDriverRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'badge',
        'score',
        'transporteur',
        'observation'
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
        return ScoreDriver::class;
    }
}
