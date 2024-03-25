<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class DataExcel
 * @package App\Models
 * @version March 25, 2024, 6:20 pm +07
 *
 * @property string $camion
 * @property string $date_debut
 * @property string $date_fin
 * @property number $delais_route
 * @property string $sigdep_reel
 * @property string $marche
 * @property string $adresse_livraison
 */
class DataExcel extends Model
{
    use SoftDeletes;


    public $table = 'data_excel';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'camion',
        'date_debut',
        'date_fin',
        'delais_route',
        'sigdep_reel',
        'marche',
        'adresse_livraison'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'camion' => 'string',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'delais_route' => 'decimal:2',
        'sigdep_reel' => 'string',
        'marche' => 'string',
        'adresse_livraison' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
