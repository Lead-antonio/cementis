<?php

namespace App\Console\Commands\Mouvement;

use Illuminate\Console\Command;
use App\Helpers\Utils;

class GetMovement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:movement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all movement in planning';

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
        $utils = new Utils();

        // Pass the current console instance to the method
        $utils->saveDriveAndStop($this);
        
        $this->info('Process completed!');
    }
}
