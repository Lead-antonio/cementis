<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ImportModel
 * @package App\Models
 * @version August 30, 2024, 12:33 pm +07
 *
 * @property string $nom
 * @property string $association
 * @property string $observation
 */
class ImportModel extends Model
{
    use SoftDeletes;


    public $table = 'import_model';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'nom',
        'model',
        'association',
        'observation',
        'model'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nom' => 'string',
        'model' => 'string',
        'association' => 'array',
        'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
