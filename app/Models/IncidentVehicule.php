<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class IncidentVehicule
 * @package App\Models
 * @version April 30, 2025, 1:23 pm UTC
 *
 * @property integer $test
 */
class IncidentVehicule extends Model
{
    use SoftDeletes;


    public $table = 'incident_vehicule';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'imei_vehicule',
        'vehicule_id',
        'chauffeur_id',
        'date_debut',
        'date_fin',
       'distance_parcourue',
       'vitesse_maximale',
       'vitesse_moyenne',
       'duree_arret',
       'duree_repos',
        'duree_conduite',   
       'duree_travail'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'imei_vehicule' => 'string',
        'vehicule_id'=> 'integer',
        'chauffeur_id'=> 'integer',
        'date_debut'=> 'datetime',
        'date_fin'=> 'datetime',
       'distance_parcourue'=> 'double',
       'vitesse_maximale'=> 'double',
       'vitesse_moyenne'=> 'double',
       'duree_arret'=> 'double',
       'duree_repos'=> 'double',
        'duree_conduite'=> 'double',   
       'duree_travail' => 'double',  
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];


    public function getDureeArretFormateeAttribute()
    {
        return CarbonInterval::seconds((int) $this->duree_arret)
            ->cascade()
            ->forHumans([
                'parts' => 3,     // max 3 éléments (ex. "2 j 3 h 20 min")
                'short' => true,  // format abrégé : "j", "h", "min", "s"
                'join' => true,   // pour éviter les virgules
            ]);
    }

    public function getDureeConduiteFormateeAttribute()
    {
        return CarbonInterval::seconds((int) $this->duree_conduite)
            ->cascade()
            ->forHumans([
                'parts' => 3,     // max 3 éléments (ex. "2 j 3 h 20 min")
                'short' => true,  // format abrégé : "j", "h", "min", "s"
                'join' => true,   // pour éviter les virgules
            ]);
    }

    
}
