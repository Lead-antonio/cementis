<?php

namespace App\DataTables;

use App\Models\ChauffeurUpdateStory;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ChauffeurUpdateStoryDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'chauffeur_update_stories.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ChauffeurUpdateStory $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ChauffeurUpdateStory $model)
    {
        return $model->newQuery()->with(['chauffeur','chauffeur_update_type','transporteur']);
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
            'chauffeur_id' => new Column(['title' => __('models/chauffeurUpdateStories.fields.chauffeur_id'), 'data' => 'chauffeur.nom']),
            'transporteur' => new Column(['title' => __('models/chauffeurUpdateStories.fields.transporteur_id'), 'data' => 'transporteur.nom']),
            'chauffeur_update_type_id' => new Column(['title' => __('models/chauffeurUpdateStories.fields.chauffeur_update_type_id'), 'data' => 'chauffeur_update_type.name']),
            // 'commentaire' => new Column(['title' => __('models/chauffeurUpdateStories.fields.commentaire'), 'data' => 'commentaire']),
            'rfid_physique' => new Column(['title' => __('models/chauffeurUpdateStories.fields.rfid'), 'data' => 'rfid']),
            'numero_badge' => new Column(['title' => __('models/chauffeurUpdateStories.fields.numero_badge'), 'data' => 'numero_badge']),
            'description' => new Column(['title' => __('models/chauffeurUpdateStories.fields.chauffeur_update_type_id'), 'data' => 'chauffeur_update_type.name']),
            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'chauffeur_update_stories_datatable_' . time();
    }
}
