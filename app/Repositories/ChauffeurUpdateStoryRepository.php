<?php

namespace App\Repositories;

use App\Models\ChauffeurUpdateStory;
use App\Repositories\BaseRepository;

/**
 * Class ChauffeurUpdateStoryRepository
 * @package App\Repositories
 * @version March 4, 2025, 8:12 am UTC
*/

class ChauffeurUpdateStoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'chauffeur_id',
        'chauffeur_update_type_id',
        'commentaire'
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
        return ChauffeurUpdateStory::class;
    }
}
