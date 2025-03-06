<?php

namespace App\Repositories;

use App\Models\PeriodSetting;
use App\Repositories\BaseRepository;

/**
 * Class PeriodSettingRepository
 * @package App\Repositories
 * @version March 4, 2025, 8:08 am UTC
*/

class PeriodSettingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'days'
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
        return PeriodSetting::class;
    }
}
