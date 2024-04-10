<?php

namespace App\Repositories;

use App\Models\Transporteur;
use App\Repositories\BaseRepository;

/**
 * Class TransporteurRepository
 * @package App\Repositories
 * @version April 9, 2024, 10:58 am CEST
*/

class TransporteurRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nom',
        'Adresse'
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
        return Transporteur::class;
    }
}
