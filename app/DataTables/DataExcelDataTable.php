<?php

namespace App\DataTables;

use App\Models\DataExcel;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class DataExcelDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'data_excels.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DataExcel $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DataExcel $model)
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
            'camion' => new Column(['title' => __('models/dataExcels.fields.camion'), 'data' => 'camion']),
            'date_debut' => new Column([
                'title' => __('models/dataExcels.fields.date_debut'),
                'data' => 'date_debut',
                'render' => "function(fll) {
                    const dateObject = new Date(full.date_debut);
                    const formattedDate = dateObject.toLocaleDateString();
                    return formattedDate;
                }",
            ]),
            'date_fin' => new Column([
                'title' => __('models/dataExcels.fields.date_fin'),
                'data' => 'date_fin',
                'render' => "function(fll) {
                    const dateObject = new Date(full.date_fin);
                    const formattedDate = dateObject.toLocaleDateString();
                    return formattedDate;
                }",
            ]),
            'delais_route' => new Column(['title' => __('models/dataExcels.fields.delais_route'), 'data' => 'delais_route']),
            'sigdep_reel' => new Column(['title' => __('models/dataExcels.fields.sigdep_reel'), 'data' => 'sigdep_reel']),
            'marche' => new Column(['title' => __('models/dataExcels.fields.marche'), 'data' => 'marche']),
            'adresse_livraison' => new Column(['title' => __('models/dataExcels.fields.adresse_livraison'), 'data' => 'adresse_livraison'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'data_excels_datatable_' . time();
    }
}
