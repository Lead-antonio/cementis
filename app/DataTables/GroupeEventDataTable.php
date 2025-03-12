<?php

namespace App\DataTables;

use App\Models\GroupeEvent;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class GroupeEventDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'groupe_events.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\GroupeEvent $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(GroupeEvent $model)
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
                    'excel', 'csv', 'pdf'
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
            'key' => new Column(['title' => __('models/groupeEvents.fields.key'), 'data' => 'key']),
            'imei' => new Column(['title' => __('models/groupeEvents.fields.imei'), 'data' => 'imei']),
            'chauffeur' => new Column(['title' => __('models/groupeEvents.fields.chauffeur'), 'data' => 'chauffeur']),
            'vehicule' => new Column(['title' => __('models/groupeEvents.fields.vehicule'), 'data' => 'vehicule']),
            'type' => new Column(['title' => __('models/groupeEvents.fields.type'), 'data' => 'type']),
            'latitude' => new Column(['title' => __('models/groupeEvents.fields.latitude'), 'data' => 'latitude']),
            'longitude' => new Column(['title' => __('models/groupeEvents.fields.longitude'), 'data' => 'longitude']),
            'duree' => new Column(['title' => __('models/groupeEvents.fields.duree'), 'data' => 'duree'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'groupe_events_datatable_' . time();
    }
}
