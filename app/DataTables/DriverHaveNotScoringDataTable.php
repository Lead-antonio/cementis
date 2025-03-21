<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\CollectionDataTable;
use Illuminate\Support\Collection;

class DriverHaveNotScoringDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new CollectionDataTable($query);

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return collect($this->data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => false,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    'excel', 'csv', 'print',
                ],
                 'language' => __('datatables'),
            ]);
    }

    /**
     * Get columns.
     * @return array
     */
    protected function getColumns()
    {
        return [
            ['data' => 'numero_badge', 'title' => 'Numéro badge'],
            ['data' => 'nom', 'title' => 'Nom chauffeur'],
            ['data' => 'update', 'title' => 'Mise à jour'],
            ['data' => 'observation', 'title' => 'Observation'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'badge_detail_' . time();
    }
}
