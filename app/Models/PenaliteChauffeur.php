<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Penalite;

/**
 * Class PenaliteChauffeur
 * @package App\Models
 * @version March 27, 2024, 6:46 pm +07
 *
 * @property integer $id
 * @property string $nom_chauffeur
 * @property string $date
 * @property integer $point_penalite
 */
class PenaliteChauffeur extends Model
{
    use SoftDeletes;


    public $table = 'penalite_chauffeur';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'nom_chauffeur',
        'date',
        'point_penalite'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nom_chauffeur' => 'string',
        'date' => 'datetime',
        'point_penalite' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
    // public function penalite()
    // {
    //     return $this->belongsTo(Penalite::class, 'penalite_id')->withTrashed();
    // }
    
}
