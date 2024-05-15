<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TempsReposHebdomadaire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repos:hebdomadaire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save infraction Temps de repos hebdomadaire';

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
        SaveTempsReposHebdomadaire();
    }
}
