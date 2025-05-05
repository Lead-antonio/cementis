<?php

namespace App\DataTables;

use App\Models\IncidentVehiculeCoordonnee;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class IncidentVehiculeCoordonneeDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'incident_vehicule_coordonnees.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\IncidentVehiculeCoordonnee $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(IncidentVehiculeCoordonnee $model)
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
                'buttons'   => ['excel', 'csv', 'print'],
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
            'latitude' => new Column(['title' => __('models/incidentVehiculeCoordonnees.fields.latitude'), 'data' => 'latitude']),
            'longitude' => new Column(['title' => __('models/incidentVehiculeCoordonnees.fields.longitude'), 'data' => 'longitude']),
            'date_heure' => new Column(['title' => __('models/incidentVehiculeCoordonnees.fields.date_heure'), 'data' => 'date_heure',
            'render' => 'function() {
                const dateObject = new Date(full.date_heure);
                if (isNaN(dateObject.getTime())) {
                    return data; 
                }
                const year = dateObject.getFullYear();
                const month = String(dateObject.getMonth() + 1).padStart(2, "0");
                const day = String(dateObject.getDate()).padStart(2, "0");
                const hours = String(dateObject.getHours()).padStart(2, "0");
                const minutes = String(dateObject.getMinutes()).padStart(2, "0");
                const formattedDate = `${day}/${month}/${year} ${hours}:${minutes}`;
                return formattedDate;
            }',

        ]),
            'vitesse' => new Column(['title' => __('models/incidentVehiculeCoordonnees.fields.vitesse'), 'data' => 'vitesse'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'incident_vehicule_coordonnees_datatable_' . time();
    }
}
