<?php

namespace App\Console\Commands\Repos;

use Illuminate\Console\Command;
use App\Helpers\Utils;
use App\Services\ReposJournalierService;
use Illuminate\Support\Facades\DB;

class checkReposJourney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repos:journey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check temps de repos minimum dans une journée de travail';

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
        $this->info('Starting the process...');
        $reposService = new ReposJournalierService();

        $lastmonth = DB::table('import_calendar')->latest('id')->first();

        $startDate = new \DateTime($lastmonth->date_debut);

        // Cloner $start_date pour ne pas modifier la date de départ
        $endDate = clone $startDate;

        // Définir la date de fin au dernier jour du mois
        $endDate->modify('last day of this month')->setTime(23, 59, 59);
        
        // Définir la date de fin (début du mois courant)
        // $endDate = new \DateTime($lastmonth->date_fin);
        // Pass the current console instance to the method
        $reposService->checkTempsReposMinInJourneyTravail($this, $startDate, $endDate);
        
        $this->info('Process completed!');
    }
}
