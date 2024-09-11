<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CalendarService;

class checkCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Matching Event with calendar';

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
        // checkCalendar();
        $calendarService = new CalendarService();
        $calendarService->checkCalendar();
    }
}
