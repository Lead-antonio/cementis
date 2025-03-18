<?php

namespace App\Console\Commands\Calendar;

use Illuminate\Console\Command;
use App\Services\CalendarService;
use App\Models\ImportCalendar;
use Illuminate\Support\Facades\DB;

class checkReposCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:repos-calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Matching Event Repos with calendar';

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
        $this->info('Starting the process...');
        $planning = DB::table('import_calendar')->latest('id')->first();


        $calendarService = new CalendarService();
        $calendarService->checkTempsReposInfractions($this, $planning);

        $this->info('Process completed!');
    }
}
