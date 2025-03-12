<?php

namespace App\DataTables;

use App\Models\Movement;
use App\Models\Vehicule;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use App\Services\TruckService;

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
        // $dataTable = new EloquentDataTable($query);
        $truckService = new TruckService();

        // return $dataTable->addColumn('action', 'movements.datatables_actions');
        return (new EloquentDataTable($query))
        ->editColumn('calendar_id', function ($model) {
            return $model->related_calendar ? $model->related_calendar->name_importation : 'Pas de planning';
        })
        ->editColumn('camion', function ($model) use ($truckService) {
            return  $truckService->getTruckPlateNumberByImei($model->imei);
        })
        ->editColumn('type', function ($model) {
            if ($model->type === "DRIVE") {
                return '<span class="badge badge-success">DRIVE</span>';
            }
            if ($model->type === "STOP") {
                return '<span class="badge badge-danger">STOP</span>';
            }
        })
        ->filterColumn('camion', function ($query, $keyword) {
            // $query->whereHas('related_calendar', function ($q) use ($keyword) {
            //     $q->whereRaw("LOWER(camion) LIKE ?", ["%{$keyword}%"]);
            // });
            $matchingImeis = Vehicule::whereRaw("LOWER(nom) LIKE ?", ["%{$keyword}%"])->pluck('imei')->toArray();
            
            // Appliquer le filtre sur les mouvements en utilisant les IMEIs correspondants
            $query->whereIn('imei', $matchingImeis);
        })
        ->rawColumns(['type', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Movement $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Movement $model)
    {
        $query = $model->newQuery()->with(['related_calendar']);
        return $query->orderBy('start_date', 'ASC')->orderBy('end_date', 'ASC')->orderBy('start_hour', 'ASC');
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
                 'language' => __('datatables'),
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    // protected function getColumns()
    // {
    //     return [
    //         'calendar_id' => new Column([
    //             'title' => __('models/movements.fields.calendar_id'), 'data' => 'calendar_id',
    //             'searchable' => true,
    //             'render' =>'function() {
    //                 if(full.calendar_id){
    //                     return full.related_calendar.name_importation;
    //                 }else{
    //                     return "Pas de planning";
    //                 }
    //             }'
    //         ]),
    //         'camion' => new Column([
    //             'title' => "Camion", 'data' => 'full.related_calendar.camion',
    //             'searchable' => true,
    //             'render' =>'function() {
    //                 if(full.calendar_id){
    //                     return full.related_calendar.camion;
    //                 }else{
    //                     return "";
    //                 }
    //             }'
    //         ]),
    //         'start_date' => new Column(['title' => __('models/movements.fields.start_date'), 'data' => 'start_date', 'searchable' => true]),
    //         'start_hour' => new Column(['title' => __('models/movements.fields.start_hour'), 'data' => 'start_hour', 'searchable' => true]),
    //         'end_date' => new Column(['title' => __('models/movements.fields.end_date'), 'data' => 'end_date', 'searchable' => true]),
    //         'end_hour' => new Column(['title' => __('models/movements.fields.end_hour'), 'data' => 'end_hour', 'searchable' => true]),
    //         'duration' => new Column(['title' => __('models/movements.fields.duration'), 'data' => 'duration', 'searchable' => true]),
    //         'type' => new Column([
    //             'title' => __('models/movements.fields.type'), 'data' => 'type',
    //             'searchable' => true,
    //              'render' =>'function() {
    //                 if(full.type == "DRIVE"){
    //                     return `<span class="badge badge-success">${full.type}</span>`;
    //                 }
    //                 if(full.type == "STOP"){
    //                     return `<span class="badge badge-danger">${full.type}</span>`;
    //                 }
    //             }'
    //         ])
    //     ];
    // }
    protected function getColumns()
    {
        return [
            'calendar_id' => new Column([
                'title' => __('models/movements.fields.calendar_id'),
                'data' => 'calendar_id',
                'searchable' => true,
                'render' => 'function() {
                    return full.related_calendar ? full.related_calendar.name_importation : "Pas de planning";
                }'
            ]),
            'camion' => new Column([
                'title' => 'Camion',
                'data' => 'camion', // Pas de référence à "full.related_calendar.camion"
                'searchable' => true,
            ]),
            'start_date' => new Column(['title' => __('models/movements.fields.start_date'), 'data' => 'start_date', 'searchable' => true]),
            'end_date' => new Column(['title' => __('models/movements.fields.end_date'), 'data' => 'end_date', 'searchable' => true]),
            'start_hour' => new Column(['title' => __('models/movements.fields.start_hour'), 'data' => 'start_hour', 'searchable' => true]),
            'end_hour' => new Column(['title' => __('models/movements.fields.end_hour'), 'data' => 'end_hour', 'searchable' => true]),
            'duration' => new Column(['title' => __('models/movements.fields.duration'), 'data' => 'duration', 'searchable' => true]),
            'type' => new Column(['title' => __('models/movements.fields.type'),'data' => 'type','searchable' => true,])
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
