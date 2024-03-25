<?php

namespace App\Repositories;

use App\Models\FichierExcel;
use App\Repositories\BaseRepository;

/**
 * Class FichierExcelRepository
 * @package App\Repositories
 * @version March 25, 2024, 5:34 pm +07
*/

class FichierExcelRepository extends BaseRepository
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
        return FichierExcel::class;
    }
}
