<?php

namespace App\DataTables;

use App\Models\PeriodSetting;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class PeriodSettingDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'period_settings.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PeriodSetting $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PeriodSetting $model)
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
                    'excel','csv','pdf'
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
            'days' => new Column(['title' => __('models/periodSettings.fields.days'), 'data' => 'days']),
            'name' => new Column(['title' => __('models/periodSettings.fields.name'), 'data' => 'name']),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'period_settings_datatable_' . time();
    }
}
