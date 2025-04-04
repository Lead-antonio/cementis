<?php

namespace App\Console\Commands\Calendar;

use Illuminate\Console\Command;
use App\Services\DriverService;

class checkDriverInCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:driver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check driver in calendars';

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
        // checkDriverInCalendar();
        $this->info('Starting the process...');
        $driverService = new DriverService();
        $driverService->checkDistanceAndRfid($this);
        $this->info('Process completed!');
    }
}
