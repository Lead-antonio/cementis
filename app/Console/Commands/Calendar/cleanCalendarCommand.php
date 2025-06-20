<?php

namespace App\Console\Commands\Calendar;

use Illuminate\Console\Command;
use App\Services\CalendarService;
use App\Models\ImportCalendar;
use Illuminate\Support\Facades\DB;

class cleanCalendarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie les doublons dans le planning pour un import_calendar_id donné';

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

        $planning = DB::table('import_calendar')->latest('id')->first();
        $this->info("Nettoyage du planning pour import_calendar_id = {$planning->name} ...");


        $calendarService = new CalendarService();
        $result = $calendarService->CleanCalendar($planning);
        if ($result['status'] === 'success') {
            $this->info($result['message']);
        } else {
            $this->error("Erreur : " . $result['message']);
        }

        return $result['status'] === 'success' ? Command::SUCCESS : Command::FAILURE;

        $this->info('Process completed!');
    }
}
