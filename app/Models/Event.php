<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Event;
use App\Models\Penalite;
use App\Models\Chauffeur;


/**
 * Class Event
 * @package App\Models
 * @version March 27, 2024, 5:27 pm +07
 *
 * @property string $chauffeur
 * @property string $type
 * @property string $description
 * @property string $date
 */
class Event extends Model
{
    use SoftDeletes;


    public $table = 'event';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'imei',
        'chauffeur',
        'vehicule',
        'type',
        'description',
        'date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'imei' => 'string',
        'chauffeur' => 'string',
        'vehicule' => 'string',
        'type' => 'string',
        'date' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
    
}
