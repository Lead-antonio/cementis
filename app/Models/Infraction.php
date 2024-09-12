<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Infraction
 * @package App\Models
 * @version May 4, 2024, 8:38 pm CEST
 *
 * @property string $imei
 * @property string $rfid
 * @property string $vehicule
 * @property string $event
 * @property number $distance
 * @property integer $duree_infraction
 * @property string $date_debut
 * @property string $date_fin
 * @property string $heure_debut
 * @property string $heure_fin
 * @property string $gps_debut
 * @property string $gps_fin
 * @property number $point
 * @property integer $duree_initial
 * @property integer $insuffisance
 */
class Infraction extends Model
{
    use SoftDeletes;


    public $table = 'infraction';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'imei',
        'rfid',
        'vehicule',
        'calendar_id',
        'event',
        'distance',
        'distance_calendar',
        'odometer',
        'duree_infraction',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'gps_debut',
        'gps_fin',
        'point',
        'duree_initial',
        'insuffisance',
        'commentaire'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'imei' => 'string',
        'rfid' => 'string',
        'calendar_id' => 'integer',
        'vehicule' => 'string',
        'event' => 'string',
        'odometer' => 'decimal:4',
        'distance' => 'decimal:4',
        'distance_calendar' => 'decimal:4',
        'duree_infraction' => 'integer',
        'date_debut' => 'string',
        'date_fin' => 'string',
        'heure_debut' => 'string',
        'heure_fin' => 'string',
        'gps_debut' => 'string',
        'gps_fin' => 'string',
        'point' => 'decimal:4',
        'duree_initial' => 'integer',
        'insuffisance' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function related_calendar(){
        return $this->belongsTo(ImportExcel::class, 'calendar_id')->withTrashed();
    }

    
}
