<?php

namespace App\Console\Commands;

use App\Services\ConduiteMaximumService;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        $startDate = Carbon::now()->subMonths(2)->endOfMonth();
        $endDate = Carbon::now()->startOfMonth();

        $drivemax->CheckDriveMaxDayAndNight($startDate, $endDate);

    }
}
