<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Penalite
 * @package App\Models
 * @version March 26, 2024, 5:28 pm +07
 *
 * @property integer $id
 * @property string $event
 * @property integer $point_penalite
 */
class Penalite extends Model
{
    use SoftDeletes;


    public $table = 'penalite';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'event',
        'duree',
        'duree_heure',
        'duree_minute',
        'duree_seconde',
        'default_value',
        'param',
        'point_penalite'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'event' => 'string',
        'duree' => 'integer',
        'duree_heure' => 'integer',
        'duree_minute' => 'integer',
        'duree_seconde' => 'integer',
        'point_penalite' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];


    
}
