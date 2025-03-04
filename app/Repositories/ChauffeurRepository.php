<?php

namespace App\Repositories;

use App\Models\Chauffeur;
use App\Repositories\BaseRepository;

/**
 * Class ChauffeurRepository
 * @package App\Repositories
 * @version March 27, 2024, 5:37 pm +07
*/

class ChauffeurRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'rfid',
        'nom',
        'contact',
        'chauffeur_update.nom'
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
        return Chauffeur::class;
    }
}
