<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Importcalendar
 * @package App\Models
 * @version March 27, 2024, 5:33 pm +07
 *
 * @property string $name
 * @property string $date_debut
 * @property string $date_fin
 * @property string $observation
 */
class Importcalendar extends Model
{
    use SoftDeletes;


    public $table = 'import_calendar';
    

    protected $dates = ['deleted_at'];

    public $timestamps = false;


    public $fillable = [
        'name',
        'date_debut',
        'date_fin',
        'observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'date_debut' => 'string',
        'date_fin' => 'string',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:Import_calendar,name',
    ];

    
}
