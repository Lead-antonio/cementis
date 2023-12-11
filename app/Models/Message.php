<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Message
 * @package App\Models
 * @version December 6, 2023, 3:34 pm +07
 *
 * @property string $contenu
 * @property string $destinataire
 * @property string $api
 */
class Message extends Model
{
    use SoftDeletes;


    public $table = 'message';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'contenu',
        'destinataire',
        'api'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contenu' => 'string',
        'destinataire' => 'string',
        'api' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
