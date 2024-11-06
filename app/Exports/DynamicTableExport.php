<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DynamicTableExport implements FromCollection, WithHeadings
{
    protected $table;
    protected $startDate;
    protected $endDate;

    public function __construct($table, $startDate = null, $endDate = null)
    {
        $this->table = $table;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Récupérer les données de la table sous forme de collection
     */
    public function collection()
    {
        $query = DB::table($this->table);

        // Appliquer le filtre de date si les dates sont fournies
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    /**
     * Récupérer les noms des colonnes de la table pour les en-têtes
     */
    public function headings(): array
    {
        return Schema::getColumnListing($this->table);
    }

    
}
