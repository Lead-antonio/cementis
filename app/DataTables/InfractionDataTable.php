<?php

namespace App\DataTables;

use App\Models\Infraction;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class InfractionDataTable extends DataTable
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

        return $dataTable;
        // ->addColumn('action', 'infractions.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Infraction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Infraction $model)
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
            // ->addAction(['width' => '120px', 'printable' => false, 'title' => __('crud.action')])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => ['excel', 'csv', 'print'],
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
            // 'imei' => new Column(['title' => __('models/infractions.fields.imei'), 'data' => 'imei']),
            'calendar_id' => new Column([
                'title' => __('models/penaliteChauffeurs.fields.id_calendar'), 'data' => 'calendar_id',
                'render' =>'function() {
                    return full.related_calendar.name_importation;
                }',
            ]),
            'rfid' => new Column(['title' => __('models/infractions.fields.rfid'), 'data' => 'rfid']),
            'vehicule' => new Column(['title' => __('models/infractions.fields.vehicule'), 'data' => 'vehicule']),
            'event' => new Column(['title' => __('models/infractions.fields.event'), 'data' => 'event']),
            'distance' => new Column(['title' => __('models/infractions.fields.distance'), 'data' => 'distance']),
            'odometer' => new Column(['title' => __('models/infractions.fields.odometer'), 'data' => 'odometer']),
            'duree_initial' => new Column(['title' => __('models/infractions.fields.duree_initial'), 'data' => 'duree_initial']),
            'duree_infraction' => new Column(['title' => __('models/infractions.fields.duree_infraction'), 'data' => 'duree_infraction']),
            'date_debut' => new Column(['title' => __('models/infractions.fields.date_debut'), 'data' => 'date_debut']),
            'heure_debut' => new Column(['title' => __('models/infractions.fields.heure_debut'), 'data' => 'heure_debut']),
            'date_fin' => new Column(['title' => __('models/infractions.fields.date_fin'), 'data' => 'date_fin']),
            'heure_fin' => new Column(['title' => __('models/infractions.fields.heure_fin'), 'data' => 'heure_fin']),
            'gps_debut' => new Column(['title' => __('models/infractions.fields.gps_debut'), 'data' => 'gps_debut']),
            'gps_fin' => new Column(['title' => __('models/infractions.fields.gps_fin'), 'data' => 'gps_fin']),
            'point' => new Column(['title' => __('models/infractions.fields.point'), 'data' => 'point']),
            'insufficance' => new Column(['title' => __('models/infractions.fields.insufficance'), 'data' => 'insufficance'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'infractions_datatable_' . time();
    }
}
