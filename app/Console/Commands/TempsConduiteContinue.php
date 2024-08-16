<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TempsConduiteContinue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'driver:continuous';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Temps de conduite continue';

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
        checkTempsConduiteContinue();
    }
}
