<?php

namespace App\Exports;

use App\Models\ImportExcel;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Scoring;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScoringCardExport implements FromCollection, WithHeadings,WithStyles
{
    protected $planning;
    protected $alphaciment_driver;

    public function __construct($planning,$alphaciment_driver)
    {
        $this->planning = $planning;
        $this->alphaciment_driver = $alphaciment_driver;
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Utiliser l'ID de planning passé, ou le plus récent si non spécifié
        $selectedPlanning = $this->planning ?? DB::table('import_calendar')->latest('id')->value('id');

        // Définir la requête de base pour récupérer les scorings
        $query = Scoring::where('id_planning', $selectedPlanning)->with(['driver','driver.latest_update', 'transporteur']);

        // Vérifier si $this->alphaciment_driver n'est pas null avant d'appliquer le filtre
        if ($this->alphaciment_driver !== null) {
            // Récupérer la liste des camions du ImportExcel en fonction du planning sélectionné
            $badgesImport = ImportExcel::where('import_calendar_id', $selectedPlanning)
                                ->pluck('badge_chauffeur') // Récupère uniquement la colonne "camion"
                                ->toArray();

            // if ($this->alphaciment_driver === "oui") {
            //     $query->where(function ($q) use ($camionsImport) {
            //         foreach ($camionsImport as $camion) {
            //             $q->orWhere('camion', 'LIKE', "%{$camion}%");
            //         }
            //     }); // Ne garder que les camions présents dans ImportExcel
            // } elseif ($this->alphaciment_driver === "non") {
            //     $query->where(function ($q) use ($camionsImport) {
            //         foreach ($camionsImport as $camion) {
            //             $q->where('camion', 'NOT LIKE', "%{$camion}%");
            //         }
            //     }); // Exclure ces camions
            // }
            if ($this->alphaciment_driver === "oui") {
                $query->whereHas('driver', function($q) use ($badgesImport) {
                    $q->whereHas('latest_update', function($query) use ($badgesImport) {
                        $query->whereIn('numero_badge', $badgesImport);  // Filtre par badge en utilisant la relation latest_update
                    })
                    ->orWhereIn('numero_badge', $badgesImport);
                });
            } elseif ($this->alphaciment_driver === "non") {
                $query->whereHas('driver', function($q) use ($badgesImport) {
                    $q->whereHas('latest_update', function($query) use ($badgesImport) {
                        $query->whereNotIn('numero_badge', $badgesImport);  // Exclure les badges présents dans ImportExcel
                    })
                    ->orWhereNotIn('numero_badge', $badgesImport);
                });
            }
        }
        
        return $query->orderBy('point', 'desc')->get()->map(function($scoring) {
            return [
                'Chauffeur' => optional($scoring->driver->latest_update)->nom ?? optional($scoring->driver)->nom ?? '',
                'Badge' => optional($scoring->driver->latest_update)->numero_badge ?? optional($scoring->driver)->numero_badge ?? '',
                'Transporteur' => $scoring->transporteur->nom ?? '',
                'Camion' => getTruckByImei($scoring->camion),
                'Scoring' => $scoring->point,
                'Infraction le plus fréquent' => getInfractionWithmaximumPoint($scoring->driver_id, $this->planning),
                'Commentaire' => $scoring->comment
            ];
        });
    }


    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Chauffeur',
            'Badge',
            'Transporteur',
            'Camion',
            'Scoring',
            'Infraction le plus fréquent',
            'Commentaire'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style pour les en-têtes de colonne
            'A1:J1' => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

}
