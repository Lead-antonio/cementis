<?php

namespace App\Console\Commands\CheckDriverNotFix;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Chauffeur;
use App\Models\PeriodSetting;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Notifications\DriversWithoutNameNotification;
use App\Mail\DriversWithoutNameMail;
use Illuminate\Support\Facades\Auth;

class CheckDriverNotFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:driver-not-fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifier par email et pop up les chaufeurs dont les non fixe pendant un certain temps';

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
        $periodSetting = PeriodSetting::first();

        if (!$periodSetting) {
            $this->info('Aucune période de vérification définie.');
            return;
        }

        $thresholdDate = now()->subDays($periodSetting->days);
        
        $drivers = Chauffeur::where('nom', 'LIKE', '%chauffeur non fix%')
        ->where('created_at', '<=', $thresholdDate)
        ->get();
        

        if ($drivers->isNotEmpty()) {
            // Envoyer un email
            Mail::to('nomenandrianinaantonio@gmail.com')->send(new DriversWithoutNameMail($drivers));

            // Envoyer une notification aux administrateurs
            $admins = User::whereHas('roles', function ($query) {
                $query->where('name', 'supper-admin');
            })->get();
            
            Notification::send($admins, new DriversWithoutNameNotification($drivers->count()));

            $this->info('Email et notification envoyés.');
        } else {
            $this->info('Aucun chauffeur concerné.');
        }
    }
}
