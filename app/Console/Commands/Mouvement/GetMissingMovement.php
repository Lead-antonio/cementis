<?php

namespace App\Console\Commands\Mouvement;

use Illuminate\Console\Command;
use App\Helpers\Utils;
use App\Services\MovementService;
use App\Services\CalendarService;
use App\Models\ImportCalendar;
use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class GetMissingMovement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:missing-movement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all missing movement in planning';

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
        $this->info('Starting the get missing movement process...');

        $movementService = new MovementService();
        $lastmonth = DB::table('import_calendar')->latest('id')->first();

        $startDate = new \DateTime($lastmonth->date_debut);

        // Définir la date de fin (début du mois courant)
        // $endDate = new \DateTime($lastmonth->date_fin);
        $endDate = clone $startDate;

            // Définir la date de fin au dernier jour du mois
        $endDate->modify('last day of this month')->setTime(23, 59, 59);



        $movementService->getMissingMouvementMonthly($this, $startDate, $endDate, $lastmonth->id);
        
        $this->info('Process completed for get all missing movement!');
    }
}
