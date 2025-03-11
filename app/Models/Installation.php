<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Installation
 * @package App\Models
 * @version July 4, 2024, 3:41 pm CEST
 *
 * @property string $date_installation
 * @property integer $vehicule_id
 * @property integer $installateur_id
 */
class Installation extends Model
{
    use SoftDeletes;


    public $table = 'installation';
    

    protected $dates = ['deleted_at'];

    public $timestamps = false;


    public $fillable = [
        'date_installation',
        'vehicule_id',
        'installateur_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date_installation' => 'date',
        'vehicule_id' => 'integer',
        'installateur_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function installation_vehicule()
    {
        return $this->belongsTo(Vehicule::class, 'vehicule_id');
    }

    public function installateurs()
    {
        return $this->belongsTo(Installateur::class, 'installateur_id');
    }

}
