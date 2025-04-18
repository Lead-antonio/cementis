<?php

namespace App\DataTables;

use App\Models\Penalite;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class PenaliteDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', 'penalites.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Penalite $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Penalite $model)
    {
        return $model->newQuery();
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
            ->addAction(['width' => '120px', 'printable' => false, 'title' => __('crud.action')])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    [
                        'excel', 'csv', 'print',
                    ],     
                ],
                 'language' => __('datatables'),
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => new Column(['title' => __('models/penalites.fields.id'), 'data' => 'id']),
            'event' => new Column(['title' => __('models/penalites.fields.event'), 'data' => 'event']),
            'param' => new Column(['title' => __('models/penalites.fields.param'), 'data' => 'param']),
            'duree_heure' => new Column(['title' => __('models/penalites.fields.duree_heure'), 'data' => 'duree_heure']),
            'duree_minute' => new Column(['title' => __('models/penalites.fields.duree_minute'), 'data' => 'duree_minute']),
            'duree_seconde' => new Column(['title' => __('models/penalites.fields.duree_seconde'), 'data' => 'duree_seconde']),
            'point_penalite' => new Column(['title' => __('models/penalites.fields.point_penalite'), 'data' => 'point_penalite'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'penalites_datatable_' . time();
    }
}
