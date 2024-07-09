<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ImportInstallation
 * @package App\Models
 * @version July 5, 2024, 7:30 am CEST
 *
 * @property string $transporteur_nom
 * @property string $transporteur_adresse
 * @property string $transporteur_tel
 * @property string $chauffeur_nom
 * @property string $chauffeur_rfid
 * @property string $chauffeur_contact
 * @property string $vehicule_nom
 * @property string $vehicule_imei
 * @property string $vehicule_description
 * @property string $installateur_matricule
 * @property string $dates
 * @property integer $import_name_id
 */
class ImportInstallation extends Model
{
    use SoftDeletes;


    public $table = 'import_installation';

    public $timestamps = false;

    protected $dates = ['deleted_at'];


    public $fillable = [
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
        'dates',
        'import_name_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'transporteur_nom' => 'string',
        'transporteur_adresse' => 'string',
        'transporteur_tel' => 'string',
        'chauffeur_nom' => 'string',
        'chauffeur_rfid' => 'string',
        'chauffeur_contact' => 'string',
        'vehicule_nom' => 'string',
        'vehicule_imei' => 'string',
        'vehicule_description' => 'string',
        'installateur_matricule' => 'string',
        'import_name_id' => 'integer',
        'dates' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function importName()
    {
        return $this->belongsTo(ImportNameInstallation::class, 'import_name_id');
    }
    
}
