<?php

namespace App\Repositories;

use App\Models\ImportInstallationError;
use App\Repositories\BaseRepository;

/**
 * Class ImportInstallationErrorRepository
 * @package App\Repositories
 * @version July 5, 2024, 1:45 pm CEST
*/

class ImportInstallationErrorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'import_name_id'
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
        return ImportInstallationError::class;
    }
}
