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

class RunStepScoringCommandJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stepId;

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
            if ($this->stepId == "1") {
                Log::info("Lancement de la commande get event");
                \Artisan::call("get:event");
            } elseif ($this->stepId == "2") {
                Log::info("Lancement de la commande get mouvement");
                \Artisan::call("get:movement");
            } elseif ($this->stepId == "3") {
                Log::info("Lancement de la commande des survitesses");
                \Artisan::call("check:overspeed");
            } elseif ($this->stepId == "4") {
                Log::info("Lancement de la commande des conduite continue");
                \Artisan::call("driver:cumul");
            } elseif ($this->stepId == "5") {
                Log::info("Lancement de la commande des repos journaliers");
                \Artisan::call("repos:journey");
            } elseif ($this->stepId == "6") {
                Log::info("Lancement de la commande des repos hebdomadaire");
                \Artisan::call("repos:hebdo");
            } elseif ($this->stepId == "7") {
                Log::info("Lancement de la commande commise dans le calendrier");
                \Artisan::call("check:calendar");
            } elseif ($this->stepId == "8") {
                Log::info("Lancement de la commande pour générer le scoring card");
                \Artisan::call("scoring:generate");
            }

            // Si aucune exception n'a été lancée, la progression est terminée
            Progression::where('step_id', $this->stepId)
                ->where('month', $currentMonth)
                ->update(['status' => 'completed']);

            Log::info("Étape {$this->stepId} marquée comme terminée pour $currentMonth.");

        } catch (\Exception $e) {
            // Si une erreur survient, marquer la progression comme error
            Progression::where('step_id', $this->stepId)
                ->where('month', $currentMonth)
                ->update(['status' => 'error']);

            Log::error("Erreur lors de l'exécution de l'étape {$this->stepId} pour $currentMonth : " . $e->getMessage());
        }
    }
}
