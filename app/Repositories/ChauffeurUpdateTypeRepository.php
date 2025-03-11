<?php

namespace App\Repositories;

use App\Models\ChauffeurUpdateType;
use App\Repositories\BaseRepository;

/**
 * Class ChauffeurUpdateTypeRepository
 * @package App\Repositories
 * @version March 4, 2025, 7:50 am UTC
*/

class ChauffeurUpdateTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return ChauffeurUpdateType::class;
    }
}
