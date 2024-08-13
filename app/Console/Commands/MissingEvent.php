<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MissingEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:miss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all missing event from API';

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
        checkMissingEvent();
    }
}
