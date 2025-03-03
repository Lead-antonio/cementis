<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ImportNameInstallation
 * @package App\Models
 * @version July 5, 2024, 8:29 am CEST
 *
 * @property string $name
 * @property string $observation
 */
class ImportNameInstallation extends Model
{
    use SoftDeletes;


    public $table = 'import_name_installation';
    

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    public $fillable = [
        'id',
        'name',
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
