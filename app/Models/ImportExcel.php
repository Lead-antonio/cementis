<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ImportExcel
 * @package App\Models
 * @version March 26, 2024, 5:14 pm +07
 *
 * @property string $name_importation
 * @property string $rfid_chauffeur
 * @property string $camion
 * @property string $date_debut
 * @property string $date_fin
 * @property number $delais_route
 * @property string $sigdep_reel
 * @property string $marche
 * @property string $adresse_livraison
 * @property integer $import_calendar_id
 * @property integer $distance
 */
class ImportExcel extends Model
{
    use SoftDeletes;


    public $table = 'import_excel';
    

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    public $fillable = [
        'name_importation',
        'rfid_chauffeur',
        'camion',
        'date_debut',
        'date_fin',
        'delais_route',
        'sigdep_reel',
        'marche',
        'adresse_livraison',
        'import_calendar_id',
        'imei',
        'distance',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_importation' => 'string',
        'rfid_chauffeur' => 'string',
        'camion' => 'string',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'delais_route' => 'decimal:2',
        'sigdep_reel' => 'string',
        'marche' => 'string',
        'adresse_livraison' => 'string',
        'import_calendar_id' => 'integer',
        'imei' => 'string',
        'distance' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

     /**
     * Méthode pour obtenir les données groupées par la colonne 'name_importation'.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function groupByName()
    {
        return self::groupBy('name_importation');
    }


    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
    

    
}
