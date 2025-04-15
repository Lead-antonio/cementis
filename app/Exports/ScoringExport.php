<?php 


namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;


class ScoringExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $scoring;
    protected $distance_totale;
    protected $totals = [];

    // public function __construct($scoring, $distance_totale)
    // {
    //     $this->scoring = $scoring;
    //     $this->distance_totale = $distance_totale;
    // }
    public function __construct($scoring)
    {
        $this->scoring = $scoring;
    }

    public function collection()
    {
        return collect($this->scoring);
    }

    public function headings(): array
    {
        return [
            'Chauffeur',
            'Transporteur',
            'Événements',
            'Date début',
            'Date fin',
            'Durée(s)',
            'Coordonnées GPS',
            'Point de pénalité',
            'Scoring Card'
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


    public function map($row): array
    {
        // dd($row);
        // Calculate and update totals
        $driver = getDriverByNumberBadge($row->badge_calendar);

        $this->totals[$driver]['point'] = ($this->totals[$driver]['point'] ?? 0) + $row->point;

        // Vérifier si la distance est déjà présente dans le tableau des valeurs uniques
        // if (!in_array($row->distance_calendar, $this->totals[$driver][   'unique_distances'] ?? [])) {
        //     // Si elle n'est pas présente, l'ajouter à la totalité et au tableau des valeurs uniques
        //     $this->totals[$driver]['distance_calendar'] = ($this->totals[$driver]['distance_calendar'] ?? 0) + $row->distance_calendar;
        //     $this->totals[$driver]['unique_distances'][] = $row->distance_calendar;
        // }

        $scoringCard = $driver === 'Total :' ? number_format(($this->totals[$driver]['point']), 2) : 0;
        $coordinates = $row->gps_debut;

        // if (!is_null($row->latitude) && !is_null($row->longitude)) {
        //     $coordinates = $row->latitude . ', ' . $row->longitude;
        // }
        
        return [
            getDriverByNumberBadge($row->badge_calendar),
            get_transporteur($row->imei, $row->camion),
            $row->infraction,
            \Carbon\Carbon::parse($row->date_debut . ' ' . $row->heure_debut)->format('d-m-Y H:i:s'),
            \Carbon\Carbon::parse($row->date_fin . ' ' . $row->heure_fin)->format('d-m-Y H:i:s'),
            $row->duree_infraction,
            $coordinates,
            //$row->distance,
            $row->point,
            $scoringCard
        ];
    }


    private function calculateTotals($scoring){
        $totals = [];

        foreach ($scoring as $result) {
            $driver = getDriverByNumberBadge($row->badge_calendar);

            if (!isset($totals[$driver])) {
                $totals[$driver] = [
                    'point' => 0,
                ];
            }

            $totals[$driver]['point'] += $result->point;
        }

        return $totals;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $this->addClosingRows($event);
            },
        ];
    }

    private function addClosingRows(AfterSheet $event)
    {
        // Parcourir les chauffeurs et leurs totaux
        foreach ($this->totals as $driver => $total) {
            // Initialiser le numéro de ligne du chauffeur à 1
            $driverRow = 1;
            // Initialiser le nombre de lignes pour le chauffeur à 0
            $driverRowCount = 0;
            // Parcourir les cellules de la première colonne pour trouver le chauffeur
            while ($event->sheet->getCell('A' . $driverRow)->getValue() !== $driver) {
                $driverRow++;
            }
    
            // Récupérer le numéro de ligne de la première ligne de données pour ce chauffeur
            $firstDataRow = $driverRow + 1;
    
            // Compter le nombre de lignes pour ce chauffeur
            while ($event->sheet->getCell('A' . ($firstDataRow + $driverRowCount))->getValue() === $driver) {
                $driverRowCount++;
            }
    
            // Récupérer le numéro de ligne de la dernière ligne de données pour ce chauffeur
            $lastDataRow = $firstDataRow + $driverRowCount - 1;
    
            // Insérer une nouvelle ligne après la dernière ligne de données pour ce chauffeur
            $insertRow = $lastDataRow + 1;
    
            // $scoringCard = 0; // Valeur par défaut
            $scoringCard = number_format(($total['point']), 2);
            // if ($this->distance_totale != 0) {
            //     $scoringCard = number_format(($total['point'] != 0 ? ($total['penalty_point'] / $this->distance_totale) * 100 : 0), 2);
            // }
            
            $event->sheet->insertNewRowBefore($insertRow, 1);
    
            $event->sheet->setCellValue('A' . $insertRow, $driver); // Chauffeur
            $event->sheet->setCellValue('B' . $insertRow, ''); // Transporteur
            $event->sheet->setCellValue('C' . $insertRow, 'Total :'); // Événements
            $event->sheet->setCellValue('D' . $insertRow, ''); // Date début
            $event->sheet->setCellValue('E' . $insertRow, ''); // Date fin
            $event->sheet->setCellValue('F' . $insertRow, ''); // Durée
            $event->sheet->setCellValue('G' . $insertRow, ''); // Coordonnées
            $event->sheet->setCellValue('H' . $insertRow, $total['point']); // Point de pénalité
            // $event->sheet->setCellValue('I' . $insertRow, '0 Km');
            $event->sheet->setCellValue('I' . $insertRow, $scoringCard); // Scoring Card

    
            // Appliquer le style vert à la cellule contenant le total de point de pénalité
            // $event->sheet->getStyle('H' . $insertRow)->applyFromArray([
            //     'font' => ['bold' => true],
            //     'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00FF00']]
            // ]);
    
            // Appliquer le style orange à la cellule contenant le total de distance parcourue
            // $event->sheet->getStyle('I' . $insertRow)->applyFromArray([
            //     'font' => ['bold' => true],
            //     'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFA500']] 
            // ]);
    
            // Appliquer le style bleu à la cellule contenant le total du scoring card
            if ($scoringCard <= 2) {
                $fillColor = ['argb' => 'FF00FF00']; // Vert
            } elseif ($scoringCard > 2 && $scoringCard <= 5) {
                $fillColor = ['argb' => 'FFFFFF00']; // Jaune
            } elseif ($scoringCard > 5 && $scoringCard <= 10) {
                $fillColor = ['argb' => 'FFFFA500']; // Orange
            } else {
                $fillColor = ['argb' => 'FFFF0000']; // Rouge
            }

            $event->sheet->getStyle('I' . $insertRow)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => $fillColor]
            ]);

        }
    }


}