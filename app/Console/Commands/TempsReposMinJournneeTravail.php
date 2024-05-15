<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TempsReposMinJournneeTravail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repos:journee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chack temps de repos minimum après une journée de travail';

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
        saveReposMinimumApesJournéeTtravail();
    }
}
