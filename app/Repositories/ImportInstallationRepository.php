<?php

namespace App\Repositories;

use App\Models\ImportInstallation;
use App\Repositories\BaseRepository;

/**
 * Class ImportInstallationRepository
 * @package App\Repositories
 * @version July 5, 2024, 7:30 am CEST
*/

class ImportInstallationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'transporteur_nom',
        'transporteur_adresse',
        'transporteur_tel',
        'chauffeur_nom',
        'chauffeur_rfid',
        'chauffeur_contact',
        'vehicule_nom',
        'vehicule_imei',
        'vehicule_description',
        'installateur_matricule',
        'dates'
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
        return ImportInstallation::class;
    }
}
