<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Rotation
 * @package App\Models
 * @version December 12, 2023, 1:26 pm +07
 *
 * @property integer $id
 * @property string $matricule
 * @property string $mouvement
 * @property string $date_heur
 * @property string $coordonne_gps
 */
class Rotation extends Model
{
    use SoftDeletes;


    public $table = 'rotation';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'imei',
        'type',
        'description',
        'vehicule',
        'date_heure',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'imei' => 'string',
        'type' => 'string',
        'description' => 'string',
        'vehicule' => 'string',
        'date_heure' => 'datetime',
        'latitude' => 'string',
        'longitude' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
