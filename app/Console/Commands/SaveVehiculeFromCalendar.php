<?php

namespace App\Console\Commands;

use App\Services\GeolocalisationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SaveVehiculeFromCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicule:save';

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
        $selectedPlanning = DB::table('import_calendar')->latest('id')->value('id');
        SaveVehiculeFromCalendar($selectedPlanning);
    }
}
