<?php

namespace App\Console\Commands\ConduiteMaximum;

use Illuminate\Console\Command;
use App\Services\ConduiteMaximumService;
use App\Models\ImportExcel;
use Illuminate\Support\Facades\DB;

class TempsConduiteMaximum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:drivermax';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Temps de conduite maximum dans une journée de travail';

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

        $drivemax = new ConduiteMaximumService();
        // $drivemax->checkDrivingInfractions('865135060336425', '3B00F9C1F0');
        $drivemax->checkTempsConduiteMaximum($this);
    }
}
