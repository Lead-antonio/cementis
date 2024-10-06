<?php

namespace App\Console\Commands\Repos;

use Illuminate\Console\Command;
use App\Helpers\Utils;
use App\Services\ReposHebdoService;
use App\Models\ImportCalendar;
use Illuminate\Support\Facades\DB;

class checkReposHebdo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repos:hebdo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check temps de repos hedbomadaire dans une semaine de travail';

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
        $endDate = new \DateTime($lastmonth->date_fin);

        $repos_hebdo_service = new ReposHebdoService();

        // Pass the current console instance to the method
        $repos_hebdo_service->checkTempsReposHebdoInWeek($this, $startDate, $endDate);
        
        $this->info('Process completed!');
    }
}
