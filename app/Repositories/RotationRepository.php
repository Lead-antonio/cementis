<?php

namespace App\Repositories;

use App\Models\Rotation;
use App\Repositories\BaseRepository;

/**
 * Class RotationRepository
 * @package App\Repositories
 * @version December 12, 2023, 1:26 pm +07
*/

class RotationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'matricule',
        'mouvement',
        'date_heur',
        'coordonne_gps'
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
        return Rotation::class;
    }
}
