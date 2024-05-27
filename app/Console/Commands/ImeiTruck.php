<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImeiTruck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imei:truck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get imei truck in API';

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
        getImeiOfTruck();
    }
}
