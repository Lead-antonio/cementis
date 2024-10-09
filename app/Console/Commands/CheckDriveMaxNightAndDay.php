<?php

namespace App\Console\Commands;

use App\Services\ConduiteMaximumService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDriveMaxNightAndDay extends Command
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
        $drivemax = new ConduiteMaximumService();
        // $drivemax->checkDrivingInfractions('865135060336425', '3B00F9C1F0');
        $drivemax->checkTempsConduiteMaximum($this);

    }
}
