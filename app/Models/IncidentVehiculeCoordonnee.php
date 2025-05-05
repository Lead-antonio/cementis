<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class IncidentVehiculeCoordonnee
 * @package App\Models
 * @version April 30, 2025, 1:27 pm UTC
 *
 * @property integer $incident_vehicule_id
 * @property number $latitude
 * @property number $longitude
 * @property string $date_heure
 * @property number $vitesse
 */
class IncidentVehiculeCoordonnee extends Model
{
    use SoftDeletes;


    public $table = 'incident_vehicule_coordonnee';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'incident_vehicule_id',
        'latitude',
        'longitude',
        'date_heure',
        'vitesse'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'incident_vehicule_id' => 'integer',
        'latitude' => 'string',
        'longitude' => 'string',
        'date_heure' => 'datetime',
        'vitesse' => 'double'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
