<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckDuringMaxTimeDriving extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:maxdrive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check maximum driving time during on work daily';

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
        checkTempsReposMinJourTravail();
    }
}
