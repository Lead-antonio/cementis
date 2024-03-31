<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Event;
use App\Models\Penalite;
use App\Models\Chauffeur;


/**
 * Class Event
 * @package App\Models
 * @version March 27, 2024, 5:27 pm +07
 *
 * @property string $chauffeur
 * @property string $type
 * @property string $description
 * @property string $date
 */
class Event extends Model
{
    use SoftDeletes;


    public $table = 'event';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'imei',
        'chauffeur',
        'vehicule',
        'type',
        'description',
        'date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'imei' => 'string',
        'chauffeur' => 'string',
        'vehicule' => 'string',
        'type' => 'string',
        'date' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function createExistingDriverInEvent(){
        $existingDrivers = Event::distinct()
                            ->where(function ($query) {
                                $query->whereNotNull('chauffeur')
                                      ->where('chauffeur','<>', '');
                            })
                            ->pluck('chauffeur');
        
        $existingDrivers->each(function ($name) {
            // Utilisez firstOrCreate pour éviter les doublons
            Chauffeur::firstOrCreate(['nom' => $name]);
        });
    }


    // public function getExistingDriverInEvent(){
    //     $existingDrivers = Event::distinct()
    //                         ->where(function ($query) {
    //                             $query->whereNotNull('chauffeur')
    //                                   ->where('chauffeur','<>', '');
    //                         })
    //                         ->pluck('chauffeur');
 
    //     return $existingDrivers;
    // }

    public function getEventMonthly($Chauffeur, $month){
        $events = Event::where('chauffeur', $Chauffeur)
            ->whereMonth('date', $month)
            ->get();
        
        return $events;
    }


    public function getScoreOfPenaliteMonthly($Chauffeur, $month){
        $totalPointsPenalite = 0;

        $events = Event::where('chauffeur', $Chauffeur)
            ->whereMonth('date', $month)
            ->get();
        
        foreach ($events as $event) {
            // Récupérer le nombre de points de pénalité correspondant au type d'événement
            $typeEvent = $event->type;
            $penalite = Penalite::where('event', $typeEvent)->first();
            
            // Ajouter les points de pénalité au total
            if ($penalite) {
                $totalPointsPenalite += $penalite->point_penalite;
            }
        }

        return $totalPointsPenalite;
    }
    
}
