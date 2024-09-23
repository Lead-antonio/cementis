<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Movement
 * @package App\Models
 * @version September 9, 2024, 9:59 am CEST
 *
 * @property integer $calendar_id
 * @property string $start_date
 * @property string $start_hour
 * @property string $end_date
 * @property string $end_hour
 * @property string $duration
 * @property string $type
 */
class Movement extends Model
{
    use SoftDeletes;


    public $table = 'movement';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'calendar_id',
        'imei',
        'rfid',
        'start_date',
        'start_hour',
        'end_date',
        'end_hour',
        'duration',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'calendar_id' => 'integer',
        'imei' => 'string',
        'rfid' => 'string',
        'start_date' => 'string',
        'end_date' => 'string',
        'type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function related_calendar()
    {
        return $this->belongsTo(ImportExcel::class, 'calendar_id');
    }
    
}
