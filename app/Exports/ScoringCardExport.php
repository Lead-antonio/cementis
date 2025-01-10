<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Scoring;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScoringCardExport implements FromCollection, WithHeadings,WithStyles
{
    protected $planning;

    public function __construct($planning)
    {
        $this->planning = $planning;
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Utiliser l'ID de planning passé, ou le plus récent si non spécifié
        $selectedPlanning = $this->planning ?? DB::table('import_calendar')->latest('id')->value('id');

        return Scoring::where('id_planning', $selectedPlanning)
                      ->with(['driver', 'transporteur'])
                      ->get()
                      ->map(function($scoring) {
                            return [
                              'Chauffeur' => $scoring->driver->nom ?? '',
                              'Transporteur' => $scoring->transporteur->nom ?? '',
                              'Camion' => $scoring->camion,
                              'Scoring' => $scoring->point,
                              'Infraction le plus fréquent' => getInfractionWithmaximumPoint($scoring->driver_id, $this->planning ),
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
