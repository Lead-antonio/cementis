<?php

namespace App\DataTables;

use App\Models\Vehicule;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class VehiculeDataTable extends DataTable
{

    public function setSelectedTransporteur($transporteurId)
    {
        $this->selectedTransporteur = $transporteurId;
    }

    public function setSelectedPlanning($id_planning)
    {
        $this->setSelectedPlanning = $id_planning;
    }
   
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', 'vehicules.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Vehicule $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Vehicule $model)
    {
        $query =  $model->newQuery()
                ->with([
                    'related_transporteur',
                    'installation',
                    'vehicule_update' => function($query) {
                        $query->latest()->limit(1);
                    }
                ])
            ->select('vehicule.*');
        if (!empty($this->setSelectedPlanning)) {
            $query->where('id_planning', $this->setSelectedPlanning);
        }

        if (!empty($this->selectedTransporteur)) {
            $query->where('id_transporteur', $this->selectedTransporteur);
        }
        

        return $query;
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
            // 'id' => new Column(['title' => __('models/vehicules.fields.id'), 'data' => 'id']),
            'nom' => new Column(['title' => __('models/vehicules.fields.nom'), 'data' => 'nom',
        ]),
        'id_transporteur' => new Column(['title' => __('models/vehicules.fields.id_transporteur'), 'data' => 'related_transporteur.nom']),
        'imei' => new Column(['title' => __('models/vehicules.fields.imei'), 'data' => 'imei']),
        'installation' => new Column(['title' => __('models/vehicules.fields.date_installation'), 
        'render' => 'function() {
            const formattedDate = "";

            if(full.installation[0]){
                const dateObject = new Date(full.installation[0].date_installation);
                if (isNaN(dateObject.getTime())) {
                    return data; 
                }
                const year = dateObject.getFullYear();
                const month = String(dateObject.getMonth() + 1).padStart(2, "0");
                const day = String(dateObject.getDate()).padStart(2, "0");
                const hours = String(dateObject.getHours()).padStart(2, "0");
                const minutes = String(dateObject.getMinutes()).padStart(2, "0");
                const formattedDate = `${day}/${month}/${year}`;
            }
            
            return formattedDate;
        }'
    
    ]),
        
    ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'vehicules_datatable_' . time();
    }
}
