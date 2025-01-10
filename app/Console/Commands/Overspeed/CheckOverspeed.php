<?php

namespace App\Console\Commands\Overspeed;

use App\Services\OverSpeedService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckOverspeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:overspeed';

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
        $overspeed = new OverSpeedService();
                
        $startDate = Carbon::now()->subMonths(2)->endOfMonth();
        $endDate = Carbon::now()->startOfMonth();

        // $date = "2024-06-01";
        // $startDate = Carbon::parse($date)->startOfMonth();
        // $endDate = Carbon::parse($date)->endOfMonth();

        $overspeed->CheckOverSpeed($startDate,$endDate);
    }
}
