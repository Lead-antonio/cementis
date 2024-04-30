<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApiToFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save Get Route to json file';

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
        ApiToFileJson();
    }
}
