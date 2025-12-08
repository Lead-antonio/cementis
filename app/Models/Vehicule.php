<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transporteur;
use Illuminate\Support\Str;


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
        'description',
        'id_planning'
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
        'description' => 'string',
        'id_planning' => 'integer'
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

    public function getUserVehicule()
    {
        $url = "www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=5AA542DBCE91297C4C3FB775895C7500&cmd=USER_GET_OBJECTS";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (!is_array($data)) {
            return []; // Gère l'erreur si besoin
        }

        $plateNumbersWithTrailingSpace = collect($data)
        ->filter(function ($item) {
            return isset($item['plate_number']) && Str::endsWith($item['plate_number'], ' ');
        })
        ->pluck('plate_number') // Récupère uniquement les plate_number
        ->values(); // Réindexe proprement

        return $plateNumbersWithTrailingSpace;
    }
}
