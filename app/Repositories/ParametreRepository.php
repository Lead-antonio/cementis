<?php

namespace App\Repositories;

use App\Models\Parametre;
use App\Repositories\BaseRepository;

/**
 * Class ParametreRepository
 * @package App\Repositories
 * @version February 6, 2024, 2:14 pm +07
*/

class ParametreRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'limite'
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
        return Parametre::class;
    }
}
