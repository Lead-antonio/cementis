<?php

namespace App\DataTables;

use App\Models\Message;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class MessageDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'messages.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Message $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Message $model)
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
                    'excel', 'csv', 'pdf'
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
            'contenu' => new Column(['title' => __('models/messages.fields.contenu'), 'data' => 'contenu']),
            'destinataire' => new Column(['title' => __('models/messages.fields.destinataire'), 'data' => 'destinataire']),
            'api' => new Column(['title' => __('models/messages.fields.api'), 'data' => 'api'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'messages_datatable_' . time();
    }
}
