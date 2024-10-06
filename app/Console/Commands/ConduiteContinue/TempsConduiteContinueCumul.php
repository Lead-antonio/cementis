<?php

namespace App\Console\Commands\ConduiteContinue;

use Illuminate\Console\Command;
use App\Services\ConduiteContinueService;
use App\Models\ImportCalendar;
use Illuminate\Support\Facades\DB;

class TempsConduiteContinueCumul extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'driver:cumul';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Temps de conduite continue';

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

        $conduiteService = new ConduiteContinueService();
        $conduiteService->checkTempsConduiteContinueCumul($this, $startDate, $endDate);
    }
}
