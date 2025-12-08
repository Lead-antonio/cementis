<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ScoreDriver
 * @package App\Models
 * @version November 11, 2025, 6:16 am UTC
 *
 * @property string $badge
 * @property number $score
 * @property integer $transporteur
 * @property string $observation
 */
class ScoreDriver extends Model
{
    use SoftDeletes;


    public $table = 'score_driver';
    
    public $timestamps = false;

    protected $dates = ['deleted_at'];



    public $fillable = [
        'badge',
        'score',
        'transporteur',
        'id_planning',
        'most_infraction',
        'observation',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'badge' => 'string',
        'score' => 'decimal:2',
        'id_planning' => 'integer',
        'most_infraction' => 'string',
        'transporteur' => 'integer'
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
        return $this->belongsTo(Chauffeur::class, 'badge', 'numero_badge');
    }


    public function company()
    {
        return $this->belongsTo(Transporteur::class, 'transporteur', 'id');
    }

    
}
