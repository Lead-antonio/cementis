<?php

namespace App\DataTables;

use App\Models\Installation;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class InstallationDataTable extends DataTable
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
        return $dataTable->addColumn('action', 'installations.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Installation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Installation $model)
    {
        return $model->newQuery()->with(['installation_vehicule','installateurs']);
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
            'vehicule_id' => new Column(['title' => __('models/installations.fields.vehicule_id'), 'data' => 'installation_vehicule.nom',
            // installation_vehicule
            
        ]),
            'installateur_id' => new Column(['title' => __('models/installations.fields.installateur_id'), 'data' => 'installateurs.matricule']),
            'date_installation' => new Column(['title' => __('models/installations.fields.date_installation'), 'data' => 'date_installation',
            'render' => 'function() {
                const dateObject = new Date(full.date_installation);
                if (isNaN(dateObject.getTime())) {
                    return data; 
                }
                const year = dateObject.getFullYear();
                const month = String(dateObject.getMonth() + 1).padStart(2, "0");
                const day = String(dateObject.getDate()).padStart(2, "0");
                const hours = String(dateObject.getHours()).padStart(2, "0");
                const minutes = String(dateObject.getMinutes()).padStart(2, "0");
                const formattedDate = `${day}/${month}/${year} ${hours}:${minutes}`;
                return formattedDate;
            }',

            ]),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'installations_datatable_' . time();
    }
}
