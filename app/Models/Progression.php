<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progression extends Model
{
    use HasFactory;

    protected $fillable = ['step_id', 'month', 'status', 'is_completed'];

    public function step()
    {
        return $this->belongsTo(Process::class);
    }
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isError()
    {
        return $this->status === 'error';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}
