<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ChauffeurUpdateType
 * @package App\Models
 * @version March 4, 2025, 7:50 am UTC
 *
 * @property string $name
 */
class ChauffeurUpdateType extends Model
{
    use SoftDeletes;


    public $table = 'chauffeur_update_type';
    

    protected $dates = ['deleted_at'];

    public $timestamps = false;

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
