<?php

namespace App\Repositories;

use App\Models\Message;
use App\Repositories\BaseRepository;

/**
 * Class MessageRepository
 * @package App\Repositories
 * @version December 6, 2023, 3:34 pm +07
*/

class MessageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contenu',
        'destinataire',
        'api'
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
        return Message::class;
    }
}
