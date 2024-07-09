<?php

namespace App\Repositories;

use App\Models\Installation;
use App\Repositories\BaseRepository;

/**
 * Class InstallationRepository
 * @package App\Repositories
 * @version July 4, 2024, 3:41 pm CEST
*/

class InstallationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date_installation',
        'vehicule_id',
        'installateur_id'
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
        return Installation::class;
    }
}
