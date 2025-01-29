<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Progression;
use Carbon\Carbon;
use App\Events\JobCompleted;
use App\Models\Process;

class RunStepScoringCommandJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stepId;

    public $timeout = 14400;

    public $maxExceptions = 10;

    protected $commands = [
        1 => "get:event",
        2 => "get:movement",
        3 => "check:overspeed",
        4 => "driver:cumul",
        5 => "repos:journey",
        6 => "repos:hebdo",
        7 => "check:calendar",
        8 => "scoring:generate",
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stepId)
    {
        $this->stepId = $stepId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        try {
            // Exécuter la commande en fonction de l'étape
            if (array_key_exists($this->stepId, $this->commands)) {
                $command = $this->commands[$this->stepId];
                Log::info("Lancement de la commande {$command}");
                \Artisan::call($command);
            } else {
                throw new \Exception("Aucune commande définie pour l'étape {$this->stepId}");
            }

            // Si aucune exception n'a été lancée, la progression est terminée
            Progression::where('step_id', $this->stepId)
                ->where('month', $currentMonth)
                ->update(['status' => 'completed']);
            $process = Process::findOrFail($this->stepId);
            broadcast(new JobCompleted($process, 'completed'));
            Log::info("Étape {$this->stepId} marquée comme terminée pour $currentMonth.");
            Log::info('Événement JobCompleted diffusé', ['stepId' => $this->stepId]);
            
        } catch (\Exception $e) {
            // Si une erreur survient, marquer la progression comme error
            Progression::where('step_id', $this->stepId)
                ->where('month', $currentMonth)
                ->update(['status' => 'error']);
            $process = Process::findOrFail($this->stepId);
            broadcast(new JobCompleted($process, 'error'));
            Log::error("Erreur lors de l'exécution de l'étape {$this->stepId} pour $currentMonth : " . $e->getMessage());
            Log::info('Événement JobCompleted diffusé', ['stepId' => $this->stepId]);
        }
    }
}
