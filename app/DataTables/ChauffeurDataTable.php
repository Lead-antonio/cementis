<?php

namespace App\DataTables;

use App\Models\Chauffeur;
use App\Models\ChauffeurUpdate;
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

        return $dataTable->addColumn('action', 'chauffeurs.datatables_actions')
            ->filterColumn('rfid', function ($query, $keyword) {
                $matchingRfid = ChauffeurUpdate::whereRaw("LOWER(rfid) LIKE ?", ["%{$keyword}%"])
                    ->pluck('rfid')
                    ->toArray();

                $query->where(function ($q) use ($keyword, $matchingRfid) {
                    $q->where('chauffeur.rfid', 'like', "%{$keyword}%")
                    ->orWhereIn('latest_update.rfid', $matchingRfid);
                });
            })
            ->filterColumn('rfid_physique', function ($query, $keyword) {
                $matchingRfid_physique = ChauffeurUpdate::whereRaw("LOWER(rfid_physique) LIKE ?", ["%{$keyword}%"])
                    ->pluck('rfid_physique')
                    ->toArray();

                $query->where(function ($q) use ($keyword, $matchingRfid_physique) {
                    $q->where('chauffeur.rfid_physique', 'like', "%{$keyword}%")
                    ->orWhereIn('latest_update.rfid_physique', $matchingRfid_physique);
                });
            })
            ->filterColumn('numero_badge', function ($query, $keyword) {
                $matchingBadges = ChauffeurUpdate::whereRaw("LOWER(numero_badge) LIKE ?", ["%{$keyword}%"])
                    ->pluck('numero_badge')
                    ->toArray();

                $query->where(function ($q) use ($keyword, $matchingBadges) {
                    $q->where('chauffeur.numero_badge', 'like', "%{$keyword}%")
                    ->orWhereIn('latest_update.numero_badge', $matchingBadges);
                });
            });

    }

    public function query(Chauffeur $model)
    {
        return $model->newQuery()
            // Sélectionner les colonnes du chauffeur
            ->select(
                'chauffeur.id', 
                'chauffeur.nom', 
                'chauffeur.rfid', 
                'chauffeur.rfid_physique', 
                'chauffeur.numero_badge', 
                'chauffeur.deleted_at', 

                // Sélectionner les colonnes de la mise à jour du chauffeur (latest_update)
                'latest_update.id as latest_update_id', 
                'latest_update.nom as latest_update_nom', 
                'latest_update.rfid as latest_update_rfid', 
                'latest_update.rfid_physique as latest_update_rfid_physique', 
                'latest_update.numero_badge as latest_update_numero_badge',

                // Joindre les informations du transporteur associé à la mise à jour du chauffeur
                'latest_update.transporteur_id as latest_update_transporteur_id',
                'updated_transporteur.nom as latest_update_transporteur_nom',

                'chauffeur.transporteur_id as chauffeur_transporteur_id',
                'chauffeur_transporteur.nom as chauffeur_transporteur_nom'
            )
            // Jointure avec la table chauffeur_updates pour obtenir la dernière mise à jour du chauffeur
            ->leftJoin('chauffeur_updates as latest_update', 'latest_update.chauffeur_id', '=', 'chauffeur.id')
            
            // Si vous voulez seulement la dernière mise à jour, vous pouvez filtrer ici si nécessaire
            // Par exemple, en récupérant la mise à jour la plus récente
            ->leftJoin('transporteur as updated_transporteur', 'updated_transporteur.id', '=', 'latest_update.transporteur_id')

            // Jointure avec le transporteur associé au chauffeur (le transporteur du chauffeur lui-même)
            ->leftJoin('transporteur as chauffeur_transporteur', 'chauffeur_transporteur.id', '=', 'chauffeur.transporteur_id')->
            with(['related_transporteur', 'latest_update']);
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
            'ancien_nom' => new Column([
                'title' => __('models/chauffeurs.fields.old_nom'),
                'data' => 'nom',  // Nom actuel du chauffeur
                'searchable' => true,
                'render' => function () {
                    return "
                        function(data, type, row) {
                            return row.nom; 
                        }
                    ";
                }
            ]),

            'nouveau_nom' => new Column([
                'title' => __('models/chauffeurs.fields.new_nom'),
                'data' => 'nom', // Nom mis à jour
                'searchable' => true,
                'render' => function () {
                    return "
                        function(data, type, row) {
                            return row.latest_update_nom ? row.latest_update_nom : '';  
                        }
                    ";
                }
            ]),

            'transporteur' => new Column([
                'title' => __('models/chauffeurs.fields.transporteur_id'),
                'data' => 'related_transporteur.nom',
                'searchable' => true,
                'render' => function () {
                    return "
                        function(data, type, row) {
                            if (row.latest_update_transporteur_nom) {
                                return row.latest_update_transporteur_nom;  
                            }
                            else {
                                return row.chauffeur_transporteur_nom;
                            }
                        }
                    ";
                }
            ]),

            'rfid' => new Column([
                'title' => __('models/chauffeurs.fields.rfid'),
                'data' => 'rfid',  // RFID de la dernière mise à jour
                'searchable' => true,
                'render' => function () {
                    return "
                        function(data, type, row) {  
                            if (row.latest_update_rfid) {
                                return row.latest_update_rfid;  
                            }
                            else {
                                return row.rfid;
                            } 
                        }
                    ";
                }
            ]),

            'rfid_physique' => new Column([
                'title' => __('models/chauffeurs.fields.rfid_physique'),
                'data' => 'rfid_physique',  // RFID physique de la dernière mise à jour
                'searchable' => true,
                'render' => function () {
                    return "
                        function(data, type, row) {
                            if (row.latest_update_rfid_physique) {
                                return row.latest_update_rfid_physique;  
                            }
                            else {
                                return row.rfid_physique;
                            } 
                        }
                    ";
                }
            ]),

            'numero_badge' => new Column([
                'title' => __('models/chauffeurs.fields.numero_badge'),
                'data' => 'numero_badge', // Pas de référence à "full.related_calendar.camion"
                'searchable' => true,
                'render' => function () {
                    return "
                        function(data, type, row) {
                            if (row.latest_update_numero_badge) {
                                return row.latest_update_numero_badge;  
                            }
                            else {
                                return row.numero_badge;
                            }
                        }
                    ";
                }
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
        return 'chauffeurs_datatable_' . time();
    }
}
