<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progression extends Model
{
    use HasFactory;

    protected $fillable = [
        'step_id','month','status','is_completed',
        'current_substep','resume_key','resume_value','log','retries'
    ];

    public function step()
    {
        return $this->belongsTo(Process::class);
    }

    public function isInProgress(){return $this->status === 'in_progress';}
    public function isCompleted() {return $this->status === 'completed';}
    public function isError() {return $this->status === 'error';}
    public function isPending() { return $this->status === 'pending';}

    // Append text with timestamp to log field and save
    public function appendLog(string $message)
    {
        $ts = now()->toDateTimeString();
        $existing = $this->log ?? '';
        $this->log = $existing . "[{$ts}] {$message}\n";
        $this->save();
    }

    public function resetForRetry()
    {
        $this->update(['current_substep' => 0, 'resume_key' => null, 'resume_value' => null, 'status' => 'pending']);
    }

    public function incrementRetries()
    {
        $this->increment('retries');
    }
}
