<?php

namespace App\Console\Commands\Scoring;

use Illuminate\Console\Command;
use App\Services\CalendarService;
use App\Models\Importcalendar;
use App\Models\Scoring;
use Illuminate\Support\Facades\DB;

class GenerateScoring extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scoring:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les données de scoring pour le planning sélectionné';

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
        $this->info('Starting the process...');
        
        $selectedPlanning = DB::table('import_calendar')->latest('id')->value('id');
        $existingScoring = Scoring::where('id_planning', $selectedPlanning)->exists();
        $import_calendar = Importcalendar::all();

        if ($existingScoring) {
            $scoring = Scoring::where('id_planning', $selectedPlanning)->orderBy('point', 'desc')->get();
        } else {
            $data = [];
            $createScoring = [];
            $results = scoring($selectedPlanning); // Appel de la fonction scoring
            
            if ($results) {
                $comment = '';
                foreach ($results as $result) {
                    // $driver = $result->driver;
                    // $event = $result->event;
                    $badge_calendar = $result->badge_calendar;
                    $badge_rfid = get_driver_by_rfid($result->rfid_conducteur);
                    $rfid_infraction = $result->rfid_conducteur;
                    $rfid_calendar = $result->rfid_calendar;
                    $camion = $result->camion;
                    $imei = $result->imei;
                    $transporteur_id = get_transporteur_by_imei($result->imei, $result->camion);
                    // $transporteur = $result->transporteur;
                    $total_point = $result->total_point;
                    // if(empty($result->driver_id) && empty($result->transporteur_id) && empty($result->badge_conducteur)){
                    //     $driver = getDriverByBadge($result->badge_calendar, $result->rfid_calendar);
                    //     if($driver['driver_id'] == NULL && $driver['transporteur_id'] == NULL && $driver['commentaire'] != NULL){
                    //         $comment = $driver['commentaire'];
                    //     }else{
                    //         $result->driver_id = $driver['driver_id'];
                    //         $result->transporteur_id = $driver['transporteur_id'];
                    //     }
                    // }

                    // if (!isset($data[$driver])) {
                        // $data[$driver] = [
                        //     'transporteur' => $transporteur,
                        //     'total_point' => $total_point,
                        //     'Accélération brusque' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'Freinage brusque' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'Excès de vitesse hors agglomération' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'Excès de vitesse en agglomération' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'Survitesse excessive' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'Survitesse sur la piste d\'Ibity' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'Survitesse sur la piste de Tritriva' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'TEMPS DE CONDUITE CONTINUE NUIT' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'TEMPS DE CONDUITE CONTINUE JOUR' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'Temps de conduite maximum dans une journée de travail' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'Temps de repos hebdomadaire' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        //     'Temps de repos minimum après une journée de travail' => ['valeur' => 0, 'duree' => 0, 'point' => 0],
                        // ];
                        // $distance = getDistanceTotalDriverInCalendar($driver, $selectedPlanning);

                        $createScoring[] = [
                            'id_planning' => $selectedPlanning,
                            // 'driver_id' => $result->driver_id,
                            'transporteur_id' => $transporteur_id,
                            // 'driver' => $driver,
                            // 'transporteur' => $transporteur,
                            'badge_rfid' => $badge_rfid,
                            'badge_calendar' => $badge_calendar,
                            'rfid_chauffeur' => $rfid_calendar,
                            'rfid_infraction' => $rfid_infraction,
                            'imei' => $imei,
                            'camion' => $camion,
                            'comment' => $comment,
                            'distance' => 0,
                            'point' => ($total_point !== null) ? $total_point : 0
                            // 'point' => ($distance != 0) ? ($total_point / $distance) * 100 : 0
                        ];
                        $comment = '';
                    // }

                    // $data[$driver][$event] = ['valeur' => $result->valeur, 'duree' => $result->duree, 'point' => $result->point];
                }
            }
            // Sauvegarder le scoring
            $this->saveScoring($createScoring);
        }

        $this->info('Process completed!');
    }

    public function saveScoring($data){
        foreach($data as $item){
            // $existingScoring = Scoring::where('id_planning', $item['id_planning'])
            //         ->where('driver_id', $item['driver_id'])
            //         ->where('transporteur_id', $item['transporteur_id'])
            //         ->first();
    
            // if ($existingScoring) {
            //     if (empty($existingScoring->camion)) {
            //         $existingScoring->camion = getPlateNumberByRfidAndTransporteur($existingScoring->driver_id, $existingScoring->transporteur_id);
            //         $existingScoring->save();
            //     }
            // }else{
            //     if (empty($item['camion'])) {
            //         $item['camion'] = getPlateNumberByRfidAndTransporteur($item['driver_id'], $item['transporteur_id']);
            //     }
            //     if(!empty($item['camion'])){
            // if(empty($item['driver_id']) && empty($item['transporteur_id'])){
            //     $driver = getDriverByBadge( $item['badge_rfid']);
            //     $item['driver_id'] = $driver['driver_id'];
            //     $item['transporteur_id'] = $driver['transporteur_id'];
            // }
                    Scoring::create([
                        'id_planning' => $item['id_planning'],
                        // 'driver_id' => $item['driver_id'],
                        'transporteur_id' => $item['transporteur_id'],
                        'camion' => $item['camion'],
                        'badge_rfid' => $item['badge_rfid'],
                        'badge_calendar' => $item['badge_calendar'],
                        'rfid_chauffeur' => $item['rfid_chauffeur'],
                        'rfid_infraction' => $item['rfid_infraction'],
                        'imei' => $item['imei'],
                        'comment' => $item['comment'],
                        'distance' => $item['distance'],
                        'point' => $item['point'],
                    ]);
        //         }
        //     }
        }
    }
}
