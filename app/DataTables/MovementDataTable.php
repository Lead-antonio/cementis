<?php

namespace App\DataTables;

use App\Models\Movement;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class MovementDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'movements.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Movement $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Movement $model)
    {
        return $model->newQuery()->with(['related_calendar']);
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
                    'excel', 'csv', 'print'
                ],
                 'language' => [
                   'url' => url('//cdn.datatables.net/plug-ins/1.10.12/i18n/French.json'),
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
            'calendar_id' => new Column([
                'title' => __('models/movements.fields.calendar_id'), 'data' => 'calendar_id',
                'render' =>'function() {
                    if(full.calendar_id){
                        return full.related_calendar.name_importation;
                    }else{
                        return "Pas de planning";
                    }
                }'
            ]),
            'camion' => new Column([
                'title' => "Camion", 'data' => 'full.related_calendar.camion',
                'searchable' => true,
                'render' =>'function() {
                    if(full.calendar_id){
                        return full.related_calendar.camion;
                    }else{
                        return "";
                    }
                }'
            ]),
            'start_date' => new Column(['title' => __('models/movements.fields.start_date'), 'data' => 'start_date']),
            'start_hour' => new Column(['title' => __('models/movements.fields.start_hour'), 'data' => 'start_hour']),
            'end_date' => new Column(['title' => __('models/movements.fields.end_date'), 'data' => 'end_date']),
            'end_hour' => new Column(['title' => __('models/movements.fields.end_hour'), 'data' => 'end_hour']),
            'duration' => new Column(['title' => __('models/movements.fields.duration'), 'data' => 'duration']),
            'type' => new Column([
                'title' => __('models/movements.fields.type'), 'data' => 'type',
                 'render' =>'function() {
                    if(full.type == "DRIVE"){
                        return `<span class="badge badge-success">${full.type}</span>`;
                    }
                    if(full.type == "STOP"){
                        return `<span class="badge badge-danger">${full.type}</span>`;
                    }
                }'
            ])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'movements_datatable_' . time();
    }
}
