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

        return $dataTable->addColumn('action', 'chauffeurs.datatables_actions')
            ->addColumn('transporteur', function ($chauffeur) {
                return $chauffeur->transporteur ? $chauffeur->transporteur->nom : '';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Chauffeur $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Chauffeur $model)
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
            // 'id' => new Column(['title' => __('models/chauffeurs.fields.id'), 'data' => 'id']),
            'transporteur' => new Column(['title' => __('models/chauffeurs.fields.transporteur_id'), 'data' => 'transporteur']),
            'nom' => new Column(['title' => __('models/chauffeurs.fields.nom'), 'data' => 'nom']),
            'rfid' => new Column(['title' => __('models/chauffeurs.fields.rfid'), 'data' => 'rfid']),
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
