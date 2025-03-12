<?php

namespace App\DataTables;

use App\Models\Chauffeur;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ChauffeurDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'chauffeurs.datatables_actions');
    }

    // Modifie le DataTable pour accepter la requête personnalisée
    public function withQuery($query)
    {
        $this->query = $query;
        return $this;
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Chauffeur $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function query(Chauffeur $model)
    // {
    //     return $model->newQuery()->with(['related_transporteur','chauffeur_update'])
    //     ->select('chauffeur.*');
    // }

    public function query(Chauffeur $model)
    {
        return $this->query->with(['related_transporteur','chauffeur_update'])->select('chauffeur.*');
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
                    [
                        'excel', 'csv', 'print',
                    ],           
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
            // 'id' => new Column(['title' => __('models/chauffeurs.fields.id'), 'data' => 'id']),
            'ancien nom' => new Column(['title' => __('models/chauffeurs.fields.old_nom'), 'data' => 'nom',]),
            // 'name' => 'chauffeur_update.nom',
            // 'render' => function () {
            //     return "
            //         function(data, type, row) {
            //             if (row.chauffeur_update && row.chauffeur_update.length > 0) {
            //                 return row.chauffeur_update[0].nom; // Affiche le nom du dernier vehicule_update
            //             }
            //             return data; // Affiche le nom original du véhicule
            //         }
            //     ";
            // }
            'nouveau nom' => new Column(['title'=> __('models/chauffeurs.fields.new_nom'), 
                'name' => 'chauffeur_update.nom',
                'render' => function () {
                    return "
                        function(data, type, row) {
                            if (row.chauffeur_update && row.chauffeur_update.length > 0) {
                                return row.chauffeur_update[0].nom;
                            }
                            return '';
                        }
                    ";
                }
            ]),
            'rfid' => new Column(['title' => __('models/chauffeurs.fields.rfid_physique'), 'data' => 'rfid_physique']),
            'rfid_physique' => new Column(['title' => __('models/chauffeurs.fields.rfid'), 'data' => 'rfid']),
            'numero_badge' => new Column(['title' => __('models/chauffeurs.fields.numero_badge'), 'data' => 'numero_badge']),
            'transporteur_id' => new Column([
                'title' => __('models/chauffeurs.fields.transporteur_id'), 'data' => 'related_transporteur.nom',
                'render' =>'function() {
                    if(full.transporteur_id){
                        return full.related_transporteur.nom;
                    }else{
                        return "Chauffeur non défini";
                    }
                }',
            ]),
            // 'contact' => new Column(['title' => __('models/chauffeurs.fields.contact'), 'data' => 'contact'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'chauffeurs_datatable_' . time();
    }
}
