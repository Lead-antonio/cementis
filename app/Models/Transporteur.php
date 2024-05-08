<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Vehicule;

/**
 * Class Transporteur
 * @package App\Models
 * @version April 9, 2024, 10:58 am CEST
 *
 * @property string $nom
 * @property string $Adresse
 */
class Transporteur extends Model
{
    use SoftDeletes;


    public $table = 'transporteur';
    
    public $timestamps = false;

    protected $dates = ['deleted_at'];



    public $fillable = [
        'nom',
        'Adresse'
    ];
    public function vehicule()
    {
        return $this->hasMany(Vehicule::class, 'id_transporteur');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nom' => 'string',
        'Adresse' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nom' => 'required',
    ];

    public function chauffeurs()
    {
        return $this->hasMany(Chauffeur::class);
    }

    
}
