<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Parametre
 * @package App\Models
 * @version February 6, 2024, 2:14 pm +07
 *
 * @property string $name
 * @property integer $limite
 */
class Parametre extends Model
{
    use SoftDeletes;


    public $table = 'parametre';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'color',
        'limite'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'color' => 'string',
        'limite' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
