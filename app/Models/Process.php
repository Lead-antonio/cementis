<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;

    public $table = 'processus';

    protected $fillable = [
        'name',
        'description',
        'order',
    ];

    public function progressions()
    {
        return $this->hasMany(Progression::class, 'step_id');
    }

    public function currentProgression()
    {
        $currentMonth = now()->format('Y-m');
        return $this->progressions()->where('month', $currentMonth)->first();
    }
}
