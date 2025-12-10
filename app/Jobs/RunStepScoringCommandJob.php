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
use App\Models\RunJob;
use Illuminate\Support\Facades\Artisan;

class RunStepScoringCommandJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stepId;

    public $timeout = 14400;

    public $maxExceptions = 10;

    protected $commands = [
        1 => "clean:calendar",
        2 => "vehicule:save",
        3 => "update:calendar-date",
        4 => "get:event",
        5 => "get:movement",
        6 => "check:overspeed",
        7 => "driver:cumul",
        8 => "repos:journey",
        9 => "repos:hebdo",
        10 => "generate:score-drive",
        11 => "scoring:generate",
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stepId)
    {
        $this->stepId = (int)$stepId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    // public function handle()
    // {
    //     $currentMonth = Carbon::now()->format('Y-m');
    //     $runJob = RunJob::firstOrCreate([], ['is_running' => false]);

    //     if ($runJob->is_running) {
    //         Log::warning("Job déjà en cours, annulation de l'étape {$this->stepId}.");
    //         return;
    //     }

    //     try {
    //         $runJob->update(['is_running' => true]);

    //         // Exécuter la commande en fonction de l'étape
    //         if (array_key_exists($this->stepId, $this->commands)) {
    //             $command = $this->commands[$this->stepId];
    //             Log::info("Lancement de la commande {$command}");
    //             \Artisan::call($command);
    //         } else {
    //             Log::info("Job libéré : run_jobs.is_running remis à false.");
    //             throw new \Exception("Aucune commande définie pour l'étape {$this->stepId}");
    //         }

    //         // Si aucune exception n'a été lancée, la progression est terminée
    //         Progression::where('step_id', $this->stepId)
    //             ->where('month', $currentMonth)
    //             ->update(['status' => 'completed']);
    //         $process = Process::findOrFail($this->stepId);
    //         broadcast(new JobCompleted($process, 'completed'));
    //         Log::info("Étape {$this->stepId} marquée comme terminée pour $currentMonth.");
    //         Log::info('Événement JobCompleted diffusé', ['stepId' => $this->stepId]);
            
    //     } catch (\Exception $e) {
    //         // Si une erreur survient, marquer la progression comme error
    //         Progression::where('step_id', $this->stepId)
    //             ->where('month', $currentMonth)
    //             ->update(['status' => 'error']);
    //         $process = Process::findOrFail($this->stepId);
    //         broadcast(new JobCompleted($process, 'error'));
    //         Log::error("Erreur lors de l'exécution de l'étape {$this->stepId} pour $currentMonth : " . $e->getMessage());
    //         Log::info('Événement JobCompleted diffusé', ['stepId' => $this->stepId]);
    //     }finally {
    //         $runJob->update(['is_running' => false]);
    //         Log::info("Job libéré : run_jobs.is_running remis à false.");
    //     }
    // }
    // use Carbon, Log, Artisan, etc. (comme dans ton code)
   public function handle()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $runJob = RunJob::firstOrCreate([], ['is_running' => false]);

        // Empêcher les jobs simultanés
        if ($runJob->is_running) {
            Log::warning("Job déjà en cours, annulation étape {$this->stepId}.");
            return;
        }

        // Charger ou créer la progression
        $progression = Progression::firstOrCreate(
            ['step_id' => $this->stepId, 'month' => $currentMonth],
            [
                'status' => 'pending',
                'retries' => 0
            ]
        );

        try {
            // Lock job
            $runJob->update(['is_running' => true]);

            // Passer en progress
            $progression->update([
                'status' => 'in_progress',
            ]);

            if (!array_key_exists($this->stepId, $this->commands)) {
                throw new \Exception("Aucune commande définie pour l'étape {$this->stepId}");
            }

            $command = $this->commands[$this->stepId];

            $progression->appendLog("Lancement commande {$command}…");

            // 🟦 Lancer la commande SANS resume
            Artisan::call($command);

            $output = Artisan::output();
            $progression->appendLog("Commande {$command} terminée. output_len=" . strlen($output));

            
            $progression->update(['status' => 'completed']);
        } catch (\Exception $e) {

            // Marquer erreur
            $progression->update(['status' => 'error']);
            $progression->appendLog("Erreur durant l'étape {$this->stepId}: " . $e->getMessage());

            $process = Process::findOrFail($this->stepId);
            broadcast(new JobCompleted($process, 'error'));

            Log::error("Erreur étape {$this->stepId}: " . $e->getMessage());

            throw $e; // Permet de déclencher failed()

        } finally {
            // Libérer le verrou
            $runJob->update(['is_running' => false]);
            $process = Process::findOrFail($this->stepId);
            broadcast(new JobCompleted($process, $progression->status));
            Log::info("Job libéré : run_jobs.is_running = false.");
        }
    }

    /**
     * 🚨 Le job entre ici si :
     * - réseau coupé
     * - artisan s'arrête brusquement
     * - timeout API
     * - le worker queue crash / kill / restart
     */
    public function failed(\Throwable $exception)
    {
        $currentMonth = Carbon::now()->format('Y-m');

        Progression::where('step_id', $this->stepId)
            ->where('month', $currentMonth)
            ->update(['status' => 'error']);

        $process = Process::findOrFail($this->stepId);
        broadcast(new JobCompleted($process, 'error'));

        Log::error("Job {$this->stepId} failed: " . $exception->getMessage());
    }



}
