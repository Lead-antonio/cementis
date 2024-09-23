<?php

namespace App\Console\Commands\Events;

use Illuminate\Console\Command;
use App\Services\EventService;

class GetEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch events from API';

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

        $eventService = new EventService();
        $eventService->proccessEventForPeriod($this);

        $this->info('Process completed!');
    }
}
