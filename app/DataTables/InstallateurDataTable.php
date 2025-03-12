<?php

namespace App\DataTables;

use App\Models\Installateur;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class InstallateurDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'installateurs.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Installateur $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Installateur $model)
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
            'matricule' => new Column(['title' => __('models/installateurs.fields.matricule'), 'data' => 'matricule']),
            'obs' => new Column(['title' => __('models/installateurs.fields.obs'), 'data' => 'obs'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'installateurs_datatable_' . time();
    }
}
