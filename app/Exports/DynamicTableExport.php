<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DynamicTableExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $table;

    public function __construct($data, $table)
    {
        $this->data = $data;
        $this->table = $table;
    }

    /**
     * Récupérer les données de la table sous forme de collection
     */
    public function collection()
    {
        return collect($this->data); // Utiliser les données déjà filtrées
    }

    /**
     * Récupérer les noms des colonnes de la table pour les en-têtes
     */
    public function headings(): array
    {
        return Schema::getColumnListing($this->table); // Utiliser le nom de la table pour obtenir les colonnes
    }
}