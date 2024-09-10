<?php

namespace App\Repositories;

use App\Models\ImportModel;
use App\Repositories\BaseRepository;

/**
 * Class ImportModelRepository
 * @package App\Repositories
 * @version August 30, 2024, 12:34 pm +07
*/

class ImportModelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nom',
        'association',
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
        return ImportModel::class;
    }
}
