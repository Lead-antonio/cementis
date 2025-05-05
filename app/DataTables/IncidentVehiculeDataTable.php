<?php

namespace App\DataTables;

use App\Models\IncidentVehicule;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class IncidentVehiculeDataTable extends DataTable
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

        $dataTable->addColumn('duree_arret_formatee', function($row) {
            return $row->duree_arret_formatee;
        });

        $dataTable->addColumn('duree_conduite_formatee', function($row) {
            return $row->duree_conduite_formatee;
        });

        return $dataTable->addColumn('action', 'incident_vehicules.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\IncidentVehicule $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(IncidentVehicule $model)
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
            'imei_vehicule' => new Column(['title' => __('models/incidentVehicules.fields.imei_vehicule'), 'data' => 'imei_vehicule']),
            'date_debut' => new Column(['title' => __('models/incidentVehicules.fields.date_debut'), 'data' => 'date_debut',
            'render' => 'function() {
                    const dateObject = new Date(full.date_debut);
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
            'date_fin' => new Column(['title' => __('models/incidentVehicules.fields.date_fin'), 'data' => 'date_fin',
            'render' => 'function() {
                    const dateObject = new Date(full.date_fin);
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
            'distance_parcourue' => new Column(['title' => __('models/incidentVehicules.fields.distance_parcourue'), 'data' => 'distance_parcourue',]),
            'vitesse_maximale' => new Column(['title' => __('models/incidentVehicules.fields.vitesse_maximale'), 'data' => 'vitesse_maximale']),
            'vitesse_moyenne' => new Column(['title' => __('models/incidentVehicules.fields.vitesse_moyenne'), 'data' => 'vitesse_moyenne']),
            'duree_arret_formatee' => new Column([
                'title' => __('models/incidentVehicules.fields.duree_arret'),
                'data' => 'duree_arret_formatee',
                'name' => 'duree_arret', // pour que le tri fonctionne sur la vraie colonne
            ]),

            'duree_conduite_formatee' => new Column([
                'title' => __('models/incidentVehicules.fields.duree_conduite'),
                'data' => 'duree_conduite_formatee',
                'name' => 'duree_conduite', // pour que le tri fonctionne sur la vraie colonne
            ]),
            
            // 'duree_conduite' => new Column(['title' => __('models/incidentVehicules.fields.duree_conduite'), 'data' => 'duree_conduite']),
            'duree_travail' => new Column(['title' => __('models/incidentVehicules.fields.duree_travail'), 'data' => 'duree_travail']),
          
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'incident_vehicules_datatable_' . time();
    }
}
