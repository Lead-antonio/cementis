<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\RunJob;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        \App\Console\Commands\Calendar\CheckCalendar::class,
        \App\Console\Commands\Calendar\CheckReposCalendar::class,
        \App\Console\Commands\Calendar\CheckDriverInCalendar::class,
        \App\Console\Commands\Infraction\CheckMostFrequentInfraction::class,
        \App\Console\Commands\Events\GetEvent::class,
        \App\Console\Commands\ConduiteContinue\TempsConduiteContinueNotification::class,
        \App\Console\Commands\ConduiteContinue\TempsConduiteContinueCumul::class,
        \App\Console\Commands\ConduiteMaximum\TempsConduiteMaximum::class,
        \App\Console\Commands\Mouvement\GetMovement::class,
        \App\Console\Commands\Repos\checkReposJourney::class,
        \App\Console\Commands\Overspeed\CheckOverspeed::class,
        \App\Console\Commands\Scoring\GenerateScoring::class,
        \App\Console\Commands\Scoring\GenerateScoreDrive::class,
        \App\Console\Commands\CheckDriverNotFix\CheckDriverNotFix::class,
        \App\Console\Commands\Calendar\CleanCalendarCommand::class,
        \App\Console\Commands\Mouvement\GetMissingMovement::class,
    ];

    

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $runJob = RunJob::first();
        if (!$runJob->is_running) {
            $schedule->command('queue:work');
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
