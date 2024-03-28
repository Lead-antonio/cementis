<?php

namespace App\DataTables;

use App\Models\Event;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class EventDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'events.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Event $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Event $model)
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
                    'excel', 'csv', 'print',
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
            'imei' => new Column(['title' => __('models/events.fields.imei'), 'data' => 'imei']),
            'chauffeur' => new Column(['title' => __('models/events.fields.chauffeur'), 'data' => 'chauffeur']),
            'vehicule' => new Column(['title' => __('models/events.fields.vehicule'), 'data' => 'vehicule']),
            'type' => new Column(['title' => __('models/events.fields.type'), 'data' => 'type']),
            'description' => new Column(['title' => __('models/events.fields.description'), 'data' => 'description']),
            'date' => new Column([
                'title' => __('models/events.fields.date'), 'data' => 'date',
                'render' => 'function() {
                    var date = new Date(full.date);
                    var options = { year: "numeric", month: "2-digit", day: "2-digit", hour: "2-digit", minute: "2-digit", second: "2-digit", hour12: false };
                    var date_heure = date.toLocaleString("fr-FR", options);

                    return date_heure;
                }',
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
        return 'events_datatable_' . time();
    }
}
