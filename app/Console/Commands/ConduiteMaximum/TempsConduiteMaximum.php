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
    protected $signature = 'driver:max-journey';

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

        $lastmonth = DB::table('import_calendar')->latest('id')->value('id');

        $calendar = ImportExcel::where('import_calendar_id', $lastmonth)->first();
        $conduite_max_Service = new ConduiteMaximumService();
        // $journeys = $conduite_max_Service->splitCalendarByJourney($calendar);
        // dd($journeys);
    }
}
