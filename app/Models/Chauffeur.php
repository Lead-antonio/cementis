<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Chauffeur
 * @package App\Models
 * @version March 27, 2024, 5:37 pm +07
 *
 * @property integer $id
 * @property string $rfid
 * @property string $nom
 * @property string $contact
 * @property integer $transporteur_id
 */
class Chauffeur extends Model
{

    public $table = 'chauffeur';
    
    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'rfid',
        'nom',
        'contact',
        'transporteur_id',
        'numero_badge',
        'rfid_physique'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'rfid' => 'string',
        'nom' => 'string',
        'contact' => 'string',
        'numero_badge' => 'string',
        'rfid_physique' => 'string',
        'transporteur_id' => 'integer',
    ];

    public function related_transporteur()
    {
        return $this->belongsTo(Transporteur::class, 'transporteur_id');
    }

    public function penalties()
    {
        return $this->hasMany(PenaliteChauffeur::class,'id_chauffeur');
    }

    public function chauffeur_update()
    {
        return $this->hasMany(ChauffeurUpdate::class,'chauffeur_id');
    }

    public function latest_update()
    {
        return $this->hasOne(ChauffeurUpdate::class, 'chauffeur_id')->latest()->with('related_transporteur');
    }

    public function validation()
    {
        return $this->hasOne(Validation::class, 'model_id')->where('model_type', self::class);
    }


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'rfid' => 'required|unique:chauffeur,rfid'
    ];
}
