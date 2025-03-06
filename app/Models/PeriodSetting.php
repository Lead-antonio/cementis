<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class PeriodSetting
 * @package App\Models
 * @version March 4, 2025, 8:08 am UTC
 *
 * @property string $name
 * @property integer $days
 */
class PeriodSetting extends Model
{
    use SoftDeletes;


    public $table = 'periode_setting';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'days'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'days' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
