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
    protected $totals = [];

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
            'Date de l\'évènement',
            'Durée(s)',
            'Coordonnées GPS',
            'Point de pénalité',
            'Distance parcourue',
            'Scoring Card'
        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [
            // Style pour les en-têtes de colonne
            'A1:I1' => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }


    public function map($row): array
    {

        dd($row);
        // Calculate and update totals
        $driver = $row->driver;

        $this->totals[$driver]['penalty_point'] = ($this->totals[$driver]['penalty_point'] ?? 0) + $row->penalty_point;

        // Vérifier si la distance est déjà présente dans le tableau des valeurs uniques
        if (!in_array($row->distance, $this->totals[$driver]['unique_distances'] ?? [])) {
            // Si elle n'est pas présente, l'ajouter à la totalité et au tableau des valeurs uniques
            $this->totals[$driver]['distance'] = ($this->totals[$driver]['distance'] ?? 0) + $row->distance;
            $this->totals[$driver]['unique_distances'][] = $row->distance;
        }

        $scoringCard = $driver === 'Total :' ? number_format(($this->totals[$driver]['penalty_point'] != 0 ? ($this->totals[$driver]['penalty_point'] / $this->totals[$driver]['distance']) * 100 : 0), 2) : '';
        $coordinates = $row->latitude . ', ' . $row->longitude;

        return [
            $row->driver,
            $row->transporteur_nom,
            $row->event,
            \Carbon\Carbon::parse($row->date_event)->format('d-m-Y H:i:s'),
            $row->duree,
            $coordinates,
            $row->penalty_point,
            $row->distance . ' Km',
            $scoringCard
        ];
    }


    private function calculateTotals($scoring){
        $totals = [];

        foreach ($scoring as $result) {
            $driver = $result->driver;

            if (!isset($totals[$driver])) {
                $totals[$driver] = [
                    'penalty_point' => 0,
                    'distance' => 0,
                ];
            }

            $totals[$driver]['penalty_point'] += $result->penalty_point;
            $totals[$driver]['distance'] += $result->distance;
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
    
            $scoringCard = number_format(($total['penalty_point'] != 0 ? ($total['penalty_point'] / $total['distance']) * 100 : 0), 2);
            
            $event->sheet->insertNewRowBefore($insertRow, 1);
    
            $event->sheet->setCellValue('A' . $insertRow, $driver); // Chauffeur
            $event->sheet->setCellValue('B' . $insertRow, ''); // Transporteur
            $event->sheet->setCellValue('C' . $insertRow, 'Total :'); // Événements
            $event->sheet->setCellValue('D' . $insertRow, ''); // Date de l'évènement
            $event->sheet->setCellValue('E' . $insertRow, ''); // Durée(s)
            $event->sheet->setCellValue('F' . $insertRow, ''); // Coordonnées GPS
            $event->sheet->setCellValue('G' . $insertRow, $total['penalty_point']); // Point de pénalité
            $event->sheet->setCellValue('H' . $insertRow, $total['distance'] . ' Km'); // Distance parcourue
            $event->sheet->setCellValue('I' . $insertRow, $scoringCard); // Scoring Card
    
            // Appliquer le style vert à la cellule contenant le total de point de pénalité
            $event->sheet->getStyle('G' . $insertRow)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00FF00']]
            ]);
    
            // Appliquer le style orange à la cellule contenant le total de distance parcourue
            $event->sheet->getStyle('H' . $insertRow)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFA500']] 
            ]);
    
            // Appliquer le style bleu à la cellule contenant le total du scoring card
            $fillColor = ['argb' => 'FF6495ED']; // ARGB pour Blue

            $event->sheet->getStyle('I' . $insertRow)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => $fillColor]
            ]);

        }
    }


}