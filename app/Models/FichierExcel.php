<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class FichierExcel
 * @package App\Models
 * @version March 25, 2024, 5:34 pm +07
 *
 * @property integer $name
 */
class FichierExcel extends Model
{
    use SoftDeletes;


    public $table = 'fichier_Excel';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
