<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Vehicule
 * @package App\Models
 * @version May 7, 2024, 10:10 am CEST
 *
 * @property integer $id
 * @property string $nom
 */
class Vehicule extends Model
{
    use SoftDeletes;


    public $table = 'vehicule';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'nom'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nom' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
