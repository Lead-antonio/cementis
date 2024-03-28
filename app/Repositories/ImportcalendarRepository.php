<?php

namespace App\Repositories;

use App\Models\Importcalendar;
use App\Repositories\BaseRepository;

/**
 * Class ImportcalendarRepository
 * @package App\Repositories
 * @version March 27, 2024, 5:33 pm +07
*/

class ImportcalendarRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'date_debut',
        'date_fin',
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
        return Importcalendar::class;
    }
}
