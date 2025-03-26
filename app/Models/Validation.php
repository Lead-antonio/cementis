<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Validation extends Model
{
    use HasFactory;

    public $table = 'validations';

    protected $fillable = ['operator_id', 'admin_id', 'model_type', 'model_id', 'modifications', 'status','action_type','commentaire','observation'];

    protected $casts = [
        'modifications' => 'array', 
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function model()
    {
        return $this->morphTo(null, 'model_type', 'model_id');
    }
}
