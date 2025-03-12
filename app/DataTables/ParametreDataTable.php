<?php

namespace App\DataTables;

use App\Models\Parametre;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ParametreDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'parametres.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Parametre $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Parametre $model)
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
            'name' => new Column(['title' => __('models/parametres.fields.name'), 'data' => 'name']),
            'limite' => new Column([
                'title' => __('models/parametres.fields.limite'), 'data' => 'limite',
                'render' =>'function() {
                    if (full.limite !== null) {
                        return full.limite + " heure(s)";
                    } else {
                        return "Aucune limite"; 
                    }
                    
                }'
                ]),
            'color' => new Column(['title' => __('models/parametres.fields.color'), 'data' => 'color'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'parametres_datatable_' . time();
    }
}
