<?php

namespace App\Console\Commands\Infraction;

use Illuminate\Console\Command;
use App\Services\ScoreDriverService;
use App\Models\Importcalendar;
use App\Models\ScoreDriver;
use Illuminate\Support\Facades\DB;

class CheckMostFrequentInfraction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:most-infraction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'L\infraction plus fréquent';

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
        $this->info('Starting the process most frequent infraction');

        $calendar = DB::table('import_calendar')->latest('id')->first();

        $service = new ScoreDriverService();

        $rep = $service->updateAllMostInfractions($calendar->id);
        
        $this->info('Process completed for most frequent infraction!');
    }
}
