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
        return $this->query->with(['related_transporteur','chauffeur_update','latestUpdate'])
        ->leftJoin('validations', function ($join) {
            $join->on('chauffeur.id', '=', 'validations.model_id')
                 ->where('validations.model_type', '=', Chauffeur::class);
                //  ->where('validations.status', '=', 'pending');
        })
        ->select('chauffeur.*', 'validations.status as validation_status');
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

            'transporteur' => new Column([
                'title' => __('models/chauffeurs.fields.transporteur_id'),
                'name' => 'latestUpdate.transporteur_id',
                'render' => function () {
                    return "
                        function(data, type, row) {
                            if (row.latestUpdate && row.latestUpdate.related_transporteur) {
                                return row.latestUpdate.related_transporteur.nom;
                            }
                            if (row.related_transporteur) {
                                return row.related_transporteur.nom;
                            }
                            return 'Chauffeur non défini';
                        }
                    ";
                }
            ]),
            

            'rfid' => new Column([
                'title' => __('models/chauffeurs.fields.rfid'),
                'name' => 'latestUpdate.rfid',
                'render' => function () {
                    return "
                        function(data, type, row) {
                            if (row.latestUpdate) {
                                return row.latestUpdate.rfid;
                            }
                            return row.rfid;
                        }
                    ";
                }
            ]),
    
            'rfid_physique' => new Column([
                'title' => __('models/chauffeurs.fields.rfid_physique'),
                'name' => 'latestUpdate.rfid_physique',
                'render' => function () {
                    return "
                        function(data, type, row) {
                            if (row.latestUpdate) {
                                return row.latestUpdate.rfid_physique;
                            }
                            return row.rfid_physique;
                        }
                    ";
                }
            ]),
    
            'numero_badge' => new Column([
                'title' => __('models/chauffeurs.fields.numero_badge'),
                'name' => 'latestUpdate.numero_badge',
                'render' => function () {
                    return "
                        function(data, type, row) {
                            if (row.latestUpdate) {
                                return row.latestUpdate.numero_badge;
                            }
                            return row.numero_badge;
                        }
                    ";
                }
            ]),

            'Statut' => new Column([
                'title' => 'Statut',
                'data' => 'validation_status',
                'name' => 'validations.status', // Permet à DataTables de retrouver la colonne
                'render' => function () {
                    return "
                        function(data, type, row) {
                            console.log(data);
                            if (data === 'pending') {
                                return '<span class=\"badge badge-warning\">En attente</span>';
                            } else if (data === 'approved') {
                                return '<span class=\"badge badge-success\">Validé</span>';
                            } else if (data === 'rejected') {
                                return '<span class=\"badge badge-danger\">Refuser</span>';
                            }
                            return '<span class=\"badge badge-secondary\">Aucun</span>';
                        }
                    ";
                }
            ]),
            
            
    
            // 'contact' => new Column([
            //     'title' => __('models/chauffeurs.fields.contact'),
            //     'name' => 'latestUpdate.contact',
            //     'render' => function () {
            //         return "
            //             function(data, type, row) {
            //                 if (row.latestUpdate) {
            //                     return row.latestUpdate.contact;
            //                 }
            //                 return row.contact;
            //             }
            //         ";
            //     }
            // ]),
    
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
