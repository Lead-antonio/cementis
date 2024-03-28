<?php

namespace App\DataTables;

use App\Models\PenaliteChauffeur;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class PenaliteChauffeurDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'penalite_chauffeurs.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PenaliteChauffeur $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PenaliteChauffeur $model)
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
                    [
                        'excel', 'csv', 'print',
                    ], 
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
            //'id' => new Column(['title' => __('models/penaliteChauffeurs.fields.id'), 'data' => 'id']),
           // 'nom_chauffeur' => new Column(['title' => __('models/penaliteChauffeurs.fields.nom_chauffeur'), 'data' => 'nom_chauffeur']),
           'rfid' => new Column(['title' => __('models/penaliteChauffeurs.fields.rfid'), 'data' => 'chauffeur.rfid', 'searchable' => false, 'orderable' => false]),
           'id_calendar' => new Column(['title' => __('models/penaliteChauffeurs.fields.id_calendar'), 'data' => 'import_excel.id', 'searchable' => false, 'orderable' => false]),
           'event' => new Column(['title' => __('models/penaliteChauffeurs.fields.event'), 'data' => 'penalite.event', 'searchable' => false, 'orderable' => false]),
            'date' => new Column ([
                'title' => __('models/penaliteChauffeurs.fields.date'), 
                'data' => 'date',
                'render' =>'function() {
                    var dataCreated = full.created_at;
                    var created_at = moment(dataCreated).format("YYYY-MM-DD HH:mm:ss");
                    return created_at;
                }',
            ]),
            'point_penalite' => new Column(['title' => __('models/penaliteChauffeurs.fields.point_penalite'), 'data' => 'point_penalite'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'penalite_chauffeurs_datatable_' . time();
    }
}
