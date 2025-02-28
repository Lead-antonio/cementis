<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChauffeurUpdate extends Model
{
    use HasFactory;

    public $table = 'chauffeur_updates';

    public $fillable = [
        'id',
        'chauffeur_id',
        'rfid',
        'nom',
        'contact',
        'transporteur_id',
        'date_installation'
    ];
}
