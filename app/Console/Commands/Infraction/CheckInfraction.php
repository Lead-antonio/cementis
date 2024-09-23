<?php

namespace App\Console\Commands\Infraction;

use Illuminate\Console\Command;
use App\Services\InfractionService;

class CheckInfraction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:infraction';

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
        // saveInfraction();
        $this->info('Starting the process...');

        $infractionService = new InfractionService();
        $infractionService->saveInfraction($this);

        $this->info('Process completed!');
    }
}
