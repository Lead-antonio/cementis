<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ChauffeurUpdateStory
 * @package App\Models
 * @version March 4, 2025, 8:12 am UTC
 *
 * @property integer $chauffeur_id
 * @property integer $chauffeur_update_type_id
 * @property string $commentaire
 */
class ChauffeurUpdateStory extends Model
{
    use SoftDeletes;


    public $table = 'chauffeur_update_story';
    

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    public $fillable = [
        'chauffeur_id',
        'chauffeur_update_type_id',
        'commentaire',
        'rfid',
        'nom',
        'contact',
        'transporteur_id',
        'numero_badge',
        'rfid_physique',
        'validation',
        'modifier_id',
        'validator_id',
        'created_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'chauffeur_id' => 'integer',
        'chauffeur_update_type_id' => 'integer',
        'contact' => 'string',
        'numero_badge' => 'string',
        'rfid_physique' => 'string',
        'transporteur_id' => 'integer',
        'validation' => 'boolean',

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];


    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class, 'chauffeur_id');
    }

    public function transporteur()
    {
        return $this->belongsTo(Transporteur::class, 'transporteur_id');
    }

    public function chauffeur_update_type()
    {
        return $this->belongsTo(ChauffeurUpdateType::class, 'chauffeur_update_type_id');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'modifier_id');
    }
    
    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
    
}
