<?php

namespace App\Console\Commands\Mouvement;

use Illuminate\Console\Command;
use App\Helpers\Utils;
use App\Services\MovementService;
use App\Services\CalendarService;


class GetMovement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:movement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all movement in planning';

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
        $movementService = new MovementService();

        $startDate = (new \DateTime())->modify('-2 months')->modify('last day of this month');

        // Définir la date de fin (début du mois courant)
        $endDate = (new \DateTime())->modify('first day of this month');

        // Pass the current console instance to the method
        // $movementService->saveDriveAndStop($this);
        $movementService->getAllMouvementMonthly($this, $startDate, $endDate);
        
        $this->info('Process completed!');
    }
}
