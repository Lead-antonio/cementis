<?php

namespace App\Console\Commands\Infraction;

use Illuminate\Console\Command;
use App\Services\InfractionService;
use Illuminate\Support\Facades\DB;

class CheckInfraction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:infraction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // saveInfraction();
        $this->info('Starting the process...');
        $lastmonth = DB::table('import_calendar')->latest('id')->first();

        $startDate = new \DateTime($lastmonth->date_debut);

        // Définir la date de fin (début du mois courant)
        $endDate = new \DateTime($lastmonth->date_fin);

        $infractionService = new InfractionService();
        $infractionService->saveInfraction($this, $startDate, $endDate);

        $this->info('Process completed!');
    }
}
