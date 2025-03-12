<?php

namespace App\DataTables;

use App\Models\FileUpload;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class FileUploadDataTable extends DataTable
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
        $dataTable->editColumn('file_upload',function($item){
            return asset('storage/'.$item->file_upload);
        });
        return $dataTable->addColumn('action', 'file_uploads.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\FileUpload $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(FileUpload $model)
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
                    'excel', 'csv','pdf'
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
            'name' => new Column(['title' => __('models/fileUploads.fields.name'), 'data' => 'name']),
            'file_upload' => new Column(['title' => __('models/fileUploads.fields.file_upload'), 'data' => 'file_upload'])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'file_uploads_datatable_' . time();
    }
}
