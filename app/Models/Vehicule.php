<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transporteur;


/**
 * Class Vehicule
 * @package App\Models
 * @version May 7, 2024, 10:10 am CEST
 *
 * @property integer $id
 * @property string $nom
 * @property integer $id_transporteur
 * @property string $description
 */
class Vehicule extends Model
{
    use SoftDeletes;


    public $table = 'vehicule';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'imei',
        'nom',
        'id_transporteur',
        'description',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'imei' => 'string',
        'nom' => 'string',
        'id_transporteur' => 'integer',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function related_transporteur()
    {
        return $this->belongsTo(Transporteur::class, 'id_transporteur');
    }


    public function vehicule_update()
    {
        return $this->hasMany(VehiculeUpdate::class,'vehicule_id');
    }

    public function installation()
    {
        return $this->hasMany(Installation::class,'vehicule_id');
    }

}
