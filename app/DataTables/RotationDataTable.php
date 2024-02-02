<?php

namespace App\DataTables;

use App\Models\Rotation;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class RotationDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'rotations.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Rotation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Rotation $model)
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
        // return moment(full.date_heur).format("DD-MM-YYYY HH:mm:ss");
        return [
          
            'imei' => new Column(['title' => __('models/rotations.fields.imei'), 'data' => 'imei']),
            'type' => new Column(['title' => __('models/rotations.fields.type'), 'data' => 'type']),
            'vehicule' => new Column(['title' => __('models/rotations.fields.vehicule'), 'data' => 'vehicule']),
            'description' => new Column(['title' => __('models/rotations.fields.description'), 'data' => 'description']),
            'date_heure' => new Column([
                'title' => __('models/rotations.fields.date_heure'),
                'data' => 'date_heur',
                'render' => 'function() {
                    return moment.utc(full.date_heure).local().format("DD/MM/YYYY HH:mm:ss");
                }'
            ]),
            'latitude' => new Column(['title' => __('models/rotations.fields.latitude'), 'data' => 'latitude']),
            'longitude' => new Column(['title' => __('models/rotations.fields.longitude'), 'data' => 'longitude']),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'rotations_datatable_' . time();
    }
}

