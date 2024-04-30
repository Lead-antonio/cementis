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
        return $model->newQuery()->with(['related_event', 'related_driver', 'related_calendar', 'related_penalite']);
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
            'id_calendar' => new Column([
                'title' => __('models/penaliteChauffeurs.fields.id_calendar'), 'data' => 'related_calendar.name_importation',
                'render' =>'function() {
                    return full.related_calendar.name_importation;
                }',
            ]),
            'id_chauffeur' => new Column([
                'title' => __('models/penaliteChauffeurs.fields.chauffeur'), 'data' => 'full.id_chauffeur', 'searchable' => true,
                'render' =>'function() {
                    if(full.id_chauffeur){
                        return full.related_driver.nom;
                    }else{
                        return "Aucun chauffeur";
                    }
                }',
                ]),
            'matricule' => new Column(['title' => __('models/penaliteChauffeurs.fields.matricule'), 'data' => 'related_calendar.camion']),
            'id_event' => new Column(['title' => __('models/penaliteChauffeurs.fields.event'), 'data' => 'related_event.type']),
            'distance' => new Column([
                'title' => __('models/penaliteChauffeurs.fields.distance'), 'data' => 'distance',
                'render' =>'function() {
                    return full.distance + " Km";
                }',
            ]),
            'duree' => new Column([
                'title' => __('models/penaliteChauffeurs.fields.duree'), 'data' => 'duree',
                'render' =>'function() {
                    return full.duree + " s";
                }',
            ]),
            'id_penalite' => new Column(['title' => __('models/penaliteChauffeurs.fields.point_penalite'), 'data' => 'related_penalite.point_penalite']),
            'date' => new Column ([
                'title' => __('models/penaliteChauffeurs.fields.date'), 
                'data' => 'date',
                'render' =>'function() {
                    var dateCreated = new Date(full.date);
                    dateCreated.setHours(dateCreated.getHours() - 1);
                    var created_at = moment(dateCreated).format("DD-MM-YYYY HH:mm:ss");
                    return created_at;
                }',
            ]),
            'gps' => new Column([
                'title' => __('models/events.fields.gps'), 'data' => 'latitude',
                'render' => 'function() {
                    return "<a href=\"#\" onclick=\"showMapModal(" + full.related_event.latitude + ", " + full.related_event.longitude + ", \'" + full.related_event.type + "\')\">" + full.related_event.latitude + ", " + full.related_event.longitude + "</a>";
                }',
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
        return 'penalite_chauffeurs_datatable_' . time();
    }
}
