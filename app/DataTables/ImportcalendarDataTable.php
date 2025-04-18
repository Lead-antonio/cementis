<?php

namespace App\DataTables;

use App\Models\Importcalendar;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ImportcalendarDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'importcalendars.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Importcalendar $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Importcalendar $model)
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
            'name' => new Column(['title' => __('models/importcalendars.fields.name'), 'data' => 'name']),
            'date_debut' => new Column(['title' => __('models/importcalendars.fields.date_debut'), 'data' => 'date_debut',
            'render' => 'function() {
                return full.date_debut;
            }']),

            'date_fin' => new Column(['title' => __('models/importcalendars.fields.date_fin'), 'data' => 'date_fin',
            'render' => 'function() {
                return full.date_fin;
            }'

        ]),
            'observation' => new Column(['title' => __('models/importcalendars.fields.observation'), 'data' => 'observation'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'importcalendars_datatable_' . time();
    }
}
