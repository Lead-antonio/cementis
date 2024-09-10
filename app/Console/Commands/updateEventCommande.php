<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class updateEventCommande extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update event latitude and longitude from API';

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
        // $events = Event::all();
        // foreach($events as $event){
        //     // updateLatAndLongExistingEvent($event);
        //     updateVitesse($event);
        // }
        saveInfraction();
    }
}
