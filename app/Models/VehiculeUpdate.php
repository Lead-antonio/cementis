<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculeUpdate extends Model
{
    use HasFactory;

    public $table = 'vehicule_updates';
    
    public $fillable = [
        'id',
        'vehicule_id',
        'imei',
        'nom',
        'id_transporteur',
        'description',
        'date_installation',
    ];


    public function transporteur()
    {
        return $this->belongsTo(Transporteur::class, 'id_transporteur');
    }
}
