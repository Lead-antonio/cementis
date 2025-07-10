<?php

namespace App\DataTables;

use App\Models\ImportNameInstallation;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ImportNameInstallationDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'import_name_installations.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ImportNameInstallation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ImportNameInstallation $model)
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
                'buttons'   => ['excel', 'csv', 'print'],
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
            'name' => new Column(['title' => __('models/importNameInstallations.fields.name'), 'data' => 'name']),
            // 'observation' => new Column(['title' => __('models/importNameInstallations.fields.observation'), 'data' => 'observation', 
            // 'render' => "function(){
            //     console.log(full.observation)
            //     return full.observation.replace(/\./g, '.<br>');
            // }"
            
            // ])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'import_name_installations_datatable_' . time();
    }
}
