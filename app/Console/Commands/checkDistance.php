<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class checkDistance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:distance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all distance of penality driver';

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
        distance_calendar();
    }
}
