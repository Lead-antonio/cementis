<?php

namespace App\Console\Commands\Calendar;

use App\Models\ImportExcel;
use Illuminate\Console\Command;

class UpdateDatebeginCalendarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:calendar-date';

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
        getImeibyPlateNumber();
        $importExcelData = ImportExcel::get(); 
        foreach($importExcelData as $data){
            updateDatebeginAndEndByImei($data);
        }

        $this->info('Process completed!');
    }
}
