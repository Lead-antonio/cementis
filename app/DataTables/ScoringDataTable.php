<?php

namespace App\DataTables;

use App\Models\Scoring;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ScoringDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'scorings.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Scoring $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Scoring $model)
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
                       'extend' => 'create',
                       'className' => 'btn btn-default btn-sm no-corner',
                       'text' => '<i class="fa fa-plus"></i> ' .__('auth.app.create').''
                    ],
                    [
                       'extend' => 'export',
                       'className' => 'btn btn-default btn-sm no-corner',
                       'text' => '<i class="fa fa-download"></i> ' .__('auth.app.export').''
                    ],
                    [
                       'extend' => 'print',
                       'className' => 'btn btn-default btn-sm no-corner',
                       'text' => '<i class="fa fa-print"></i> ' .__('auth.app.print').''
                    ],
                    [
                       'extend' => 'reset',
                       'className' => 'btn btn-default btn-sm no-corner',
                       'text' => '<i class="fa fa-undo"></i> ' .__('auth.app.reset').''
                    ],
                    [
                       'extend' => 'reload',
                       'className' => 'btn btn-default btn-sm no-corner',
                       'text' => '<i class="fa fa-refresh"></i> ' .__('auth.app.reload').''
                    ],
                ],
                 'language' => [
                   'url' => url('//cdn.datatables.net/plug-ins/1.10.12/i18n/English.json'),
                 ],
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
            'id_planning' => new Column(['title' => __('models/scorings.fields.id_planning'), 'data' => 'id_planning']),
            'driver_id' => new Column(['title' => __('models/scorings.fields.driver_id'), 'data' => 'driver_id']),
            'transporteur_id' => new Column(['title' => __('models/scorings.fields.transporteur_id'), 'data' => 'transporteur_id']),
            'camion' => new Column(['title' => __('models/scorings.fields.camion'), 'data' => 'camion']),
            'comment' => new Column(['title' => __('models/scorings.fields.comment'), 'data' => 'comment']),
            'distance' => new Column(['title' => __('models/scorings.fields.distance'), 'data' => 'distance']),
            'point' => new Column(['title' => __('models/scorings.fields.point'), 'data' => 'point'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'scorings_datatable_' . time();
    }
}
