<?php

namespace App\Console\Commands;

use App\Services\GeolocalisationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class SaveVehiculeFromCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicule:save';

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
        // 1️⃣ Créer un nom de fichier unique
        $logFileName = 'vehicule_' . now()->format('Y_m_d_His') . '.log';
        $logPath = storage_path('logs/' . $logFileName);
        echo $logPath;

        // 2️⃣ Créer un logger Monolog spécifique pour cette exécution
        $vehiculeLogger = new Logger('vehicule_logger');
        $vehiculeLogger->pushHandler(new StreamHandler($logPath, Logger::INFO));

        
        $vehiculeLogger->info('=== Début du traitement get:movement ===');

        $selectedPlanning = DB::table('import_calendar')->latest('id')->value('id');
        SaveVehiculeFromCalendar($selectedPlanning, $vehiculeLogger);
    }
}
