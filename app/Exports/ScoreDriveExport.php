<?php

namespace App\Exports;

use App\Models\ImportExcel;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Scoring;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\ScoreDriver;

class ScoreDriveExport implements FromCollection, WithHeadings,WithStyles
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
        
        // Définir la requête de base pour récupérer les scorings
        $query = ScoreDriver::where('id_planning', $selectedPlanning);

        
        return $query->orderBy('score', 'desc')->get()->map(function($scoring) use ($selectedPlanning) {
            return [
                'Nom du chauffeur' => $scoring?->driver?->nom,
                'N° badge' => $scoring->badge,
                'Transporteur' => $scoring?->company?->nom,
                'Scoring' => $scoring->score,
                'Infraction le plus frèquent' => $scoring->most_infraction,
                'Observation' => $scoring->comment
            ];
        });
    }


    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nom du chauffeur',
            'N° badge',
            'Transporteur',
            'Scoring',
            'Infraction le plus fréquent',
            'Observation'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style pour les en-têtes de colonne
            'A1:F1' => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

}
