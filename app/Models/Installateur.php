<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Installateur
 * @package App\Models
 * @version July 4, 2024, 3:25 pm CEST
 *
 * @property string $matricule
 * @property string $obs
 */
class Installateur extends Model
{
    use SoftDeletes;


    public $table = 'installateur';
    

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    public $fillable = [
        'matricule',
        'obs'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'matricule' => 'string',
        'obs' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
