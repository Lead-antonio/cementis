<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Penalite;
use App\Models\Chauffeur;
use App\Models\Event;
use App\Models\ImportExcel;

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
        'id_calendar',
        'id_chauffeur',
        'id_event',
        'id_penalite',
        'date',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_calendar' => 'integer',
        'id_penalite' => 'integer',
        'id_event' => 'integer',
        'id_penalite' => 'integer',
        'date' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function related_calendar(){
        return $this->belongsTo(ImportExcel::class, 'id_calendar')->withTrashed();
    }

    public function related_event(){
        return $this->belongsTo(Event::class, 'id_event')->withTrashed();
    }

    public function related_driver(){
        return $this->belongsTo(Chauffeur::class, 'id_chauffeur')->withTrashed();
    }

    public function related_penalite(){
        return $this->belongsTo(Penalite::class, 'id_penalite')->withTrashed();
    }
    
}
