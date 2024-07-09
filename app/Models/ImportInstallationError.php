<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ImportInstallationError
 * @package App\Models
 * @version July 5, 2024, 1:45 pm CEST
 *
 * @property string $name
 * @property string $contenu
 * @property integer $import_name_id
 */
class ImportInstallationError extends Model
{
    use SoftDeletes;


    public $table = 'import_installation_error';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'import_name_id',
        'contenu'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'import_name_id' => 'integer',
        'contenu' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
