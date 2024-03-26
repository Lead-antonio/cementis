<?php

namespace App\DataTables;

use App\Models\ImportExcel;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ImportExcelDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'import_excels.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ImportExcel $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ImportExcel $model)
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
                       'extend' => 'create',
                       'className' => 'btn btn-default btn-sm no-corner',
                       'text' => '<i class="fa fa-plus"></i> ' .__('auth.app.create').''
                    ],
                    [
                       'extend' => 'export',
                       'className' => 'btn btn-default btn-sm no-corner',
                       'text' => '<i class="fa fa-download"></i> ' .__('auth.app.export').''
                    ],
                    [
                       'extend' => 'print',
                       'className' => 'btn btn-default btn-sm no-corner',
                       'text' => '<i class="fa fa-print"></i> ' .__('auth.app.print').''
                    ],
                    [
                       'extend' => 'reset',
                       'className' => 'btn btn-default btn-sm no-corner',
                       'text' => '<i class="fa fa-undo"></i> ' .__('auth.app.reset').''
                    ],
                    [
                       'extend' => 'reload',
                       'className' => 'btn btn-default btn-sm no-corner',
                       'text' => '<i class="fa fa-refresh"></i> ' .__('auth.app.reload').''
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
            'name_importation' => new Column(['title' => __('models/importExcels.fields.name_importation'), 'data' => 'name_importation']),
            'rfid_chauffeur' => new Column(['title' => __('models/importExcels.fields.rfid_chauffeur'), 'data' => 'rfid_chauffeur']),
            'camion' => new Column(['title' => __('models/importExcels.fields.camion'), 'data' => 'camion']),
            'date_debut' => new Column(['title' => __('models/importExcels.fields.date_debut'), 'data' => 'date_debut']),
            'date_fin' => new Column(['title' => __('models/importExcels.fields.date_fin'), 'data' => 'date_fin']),
            'delais_route' => new Column(['title' => __('models/importExcels.fields.delais_route'), 'data' => 'delais_route']),
            'sigdep_reel' => new Column(['title' => __('models/importExcels.fields.sigdep_reel'), 'data' => 'sigdep_reel']),
            'marche' => new Column(['title' => __('models/importExcels.fields.marche'), 'data' => 'marche']),
            'adresse_livraison' => new Column(['title' => __('models/importExcels.fields.adresse_livraison'), 'data' => 'adresse_livraison'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'import_excels_datatable_' . time();
    }
}
