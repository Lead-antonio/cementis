<?php

namespace App\Console\Commands\Repos;

use Illuminate\Console\Command;
use App\Helpers\Utils;
use App\Services\ReposJournalierService;

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

        // Pass the current console instance to the method
        $reposService->checkTempsReposMinInJourneyTravail($this);
        
        $this->info('Process completed!');
    }
}
