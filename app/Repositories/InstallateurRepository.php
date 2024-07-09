<?php

namespace App\Repositories;

use App\Models\Installateur;
use App\Repositories\BaseRepository;

/**
 * Class InstallateurRepository
 * @package App\Repositories
 * @version July 4, 2024, 3:25 pm CEST
*/

class InstallateurRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'matricule',
        'obs'
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
        return Installateur::class;
    }
}
