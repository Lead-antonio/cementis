<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Chauffeur
 * @package App\Models
 * @version March 27, 2024, 5:37 pm +07
 *
 * @property integer $id
 * @property string $rfid
 * @property string $nom
 * @property string $contact
 */
class Chauffeur extends Model
{
    use SoftDeletes;


    public $table = 'chauffeur';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'rfid',
        'nom',
        'contact'
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
        'contact' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
}
