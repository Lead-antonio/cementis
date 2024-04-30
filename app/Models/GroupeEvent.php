<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class GroupeEvent
 * @package App\Models
 * @version April 24, 2024, 2:25 pm CEST
 *
 * @property string $key
 * @property string $imei
 * @property string $chauffeur
 * @property string $vehicule
 * @property string $type
 * @property number $latitude
 * @property number $longitude
 * @property integer $duree
 */
class GroupeEvent extends Model
{
    use SoftDeletes;


    public $table = 'groupe_event';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'key',
        'imei',
        'chauffeur',
        'vehicule',
        'type',
        'latitude',
        'longitude',
        'duree'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'key' => 'string',
        'imei' => 'string',
        'chauffeur' => 'string',
        'vehicule' => 'string',
        'type' => 'string',
        'duree' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
