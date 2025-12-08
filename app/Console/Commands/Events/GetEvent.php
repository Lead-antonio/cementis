<?php

namespace App\Console\Commands\Events;

use Illuminate\Console\Command;
use App\Services\EventService;
use Illuminate\Support\Facades\DB;

class GetEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:event';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch events from API';

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
        $lastmonth = DB::table('import_calendar')->latest('id')->first();

        $startDate = new \DateTime($lastmonth->date_debut);

        // Définir la date de fin (début du mois courant)
        // $endDate = new \DateTime($lastmonth->date_fin);
        $endDate = clone $startDate;

            // Définir la date de fin au dernier jour du mois
        $endDate->modify('last day of this month')->setTime(23, 59, 59);

        $eventService = new EventService();
        $eventService->proccessEventForPeriod($this, $startDate, $endDate, $lastmonth->id);

        $this->info('Process completed!');
    }
}
