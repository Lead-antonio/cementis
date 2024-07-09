<?php

namespace App\Repositories;

use App\Models\ImportNameInstallation;
use App\Repositories\BaseRepository;

/**
 * Class ImportNameInstallationRepository
 * @package App\Repositories
 * @version July 5, 2024, 8:29 am CEST
*/

class ImportNameInstallationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
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
        return ImportNameInstallation::class;
    }
}
