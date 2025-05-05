<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GeolocalisationService;
use Carbon\Carbon;

class IncidentCoordonneeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incident:coordonee_save';

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
        $GeolocalisationService = new GeolocalisationService();
        $calendar_start_date = new \DateTime('2025-04-28 06:48:20');
        $calendar_start_date = Carbon::parse("2025-04-28 06:48:20");
        $calendar_end_date = Carbon::parse("2025-04-29 06:48:10");

        $GeolocalisationService->getCoordonnateCarIncident('865135060229281',$calendar_start_date,$calendar_end_date);

        $this->info('The command was successful!');
    }
}
