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
 */
class Vehicule extends Model
{
    use SoftDeletes;


    public $table = 'vehicule';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'nom',
        'id_transporteur'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nom' => 'string',
        'id_transporteur' => 'integer'
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

    
}
