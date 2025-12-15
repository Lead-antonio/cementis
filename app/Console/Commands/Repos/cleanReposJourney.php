<?php

namespace App\Console\Commands\Repos;

use Illuminate\Console\Command;
use App\Helpers\Utils;
use App\Services\ReposJournalierService;
use Illuminate\Support\Facades\DB;

class cleanReposJourney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:repos-journey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprimer les infractions de temps de repos minimum dans une journée de travail hors calendrier';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting the process cleaning infraction repos journalier...');


        $deleted = DB::table('infraction')
        ->whereMonth('date_debut', now()->month)
        ->whereYear('date_debut', now()->year)
        ->whereMonth('date_fin', now()->month)
        ->whereYear('date_fin', now()->year)
        ->delete();

        $this->info("{$deleted} infractions supprimées pour le mois courant.");
        
        $this->info('Process completed!');
    }
}
