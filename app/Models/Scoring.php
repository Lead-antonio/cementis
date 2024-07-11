<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Scoring
 * @package App\Models
 * @version June 11, 2024, 10:00 am CEST
 *
 * @property integer $id_planning
 * @property integer $driver_id
 * @property integer $transporteur_id
 * @property string $camion
 * @property string $comment
 * @property number $distance
 * @property number $point
 */
class Scoring extends Model
{
    use SoftDeletes;

    public $table = 'scoring';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_planning',
        'driver_id',
        'transporteur_id',
        'camion',
        'comment',
        'distance',
        'point'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_planning' => 'integer',
        'driver_id' => 'integer',
        'transporteur_id' => 'integer',
        'camion' => 'string',
        'distance' => 'decimal:2',
        'point' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function driver()
    {
        return $this->belongsTo(Chauffeur::class, 'driver_id');
    }

    public function transporteur()
    {
        return $this->belongsTo(Transporteur::class, 'transporteur_id');
    }

    
}
