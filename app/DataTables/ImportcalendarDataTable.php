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
            'name' => new Column(['title' => __('models/importcalendars.fields.name'), 'data' => 'name']),
            'date_debut' => new Column(['title' => __('models/importcalendars.fields.date_debut'), 'data' => 'date_debut',
            'render' => 'function() {
                var date = new Date(full.date_debut);
                var options = { year: "numeric", month: "2-digit", day: "2-digit", hour: "2-digit", minute: "2-digit", second: "2-digit", hour12: false };
                var date_heure = date.toLocaleString("fr-FR", options);

                return date_heure;
             }']),

            'date_fin' => new Column(['title' => __('models/importcalendars.fields.date_fin'), 'data' => 'date_fin',
            'render' => 'function() {
                var date = new Date(full.date_fin);
                var options = { year: "numeric", month: "2-digit", day: "2-digit", hour: "2-digit", minute: "2-digit", second: "2-digit", hour12: false };
                var date_heure = date.toLocaleString("fr-FR", options);

                return date_heure;
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
