<?php

namespace App\Repositories;

use App\Models\PenaliteChauffeur;
use App\Repositories\BaseRepository;

/**
 * Class PenaliteChauffeurRepository
 * @package App\Repositories
 * @version March 27, 2024, 6:46 pm +07
*/

class PenaliteChauffeurRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'nom_chauffeur',
        'date',
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
        return PenaliteChauffeur::class;
    }
}
