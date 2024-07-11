<?php

namespace App\DataTables;

use App\Models\ImportInstallation;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ImportInstallationDataTable extends DataTable
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

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ImportInstallation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ImportInstallation $model)
    {
        $import_name_id = request()->route('id');

        if($import_name_id ==null ){
            return $model->newQuery();
        }else{
             // Filtrer les données par import_name_id
            return $model->newQuery()->where('import_name_id', $import_name_id);
        }
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
            // ->addAction(['width' => '120px', 'printable' => false, 'title' => __('crud.action')])
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
            'transporteur_nom' => new Column(['title' => __('models/importInstallations.fields.transporteur_nom'), 'data' => 'transporteur_nom']),
            'transporteur_adresse' => new Column(['title' => __('models/importInstallations.fields.transporteur_adresse'), 'data' => 'transporteur_adresse']),
            'transporteur_tel' => new Column(['title' => __('models/importInstallations.fields.transporteur_tel'), 'data' => 'transporteur_tel']),
            'chauffeur_nom' => new Column(['title' => __('models/importInstallations.fields.chauffeur_nom'), 'data' => 'chauffeur_nom']),
            'chauffeur_rfid' => new Column(['title' => __('models/importInstallations.fields.chauffeur_rfid'), 'data' => 'chauffeur_rfid']),
            'chauffeur_contact' => new Column(['title' => __('models/importInstallations.fields.chauffeur_contact'), 'data' => 'chauffeur_contact']),
            'vehicule_nom' => new Column(['title' => __('models/importInstallations.fields.vehicule_nom'), 'data' => 'vehicule_nom']),
            'vehicule_imei' => new Column(['title' => __('models/importInstallations.fields.vehicule_imei'), 'data' => 'vehicule_imei']),
            'vehicule_description' => new Column(['title' => __('models/importInstallations.fields.vehicule_description'), 'data' => 'vehicule_description']),
            'installateur_matricule' => new Column(['title' => __('models/importInstallations.fields.installateur_matricule'), 'data' => 'installateur_matricule']),
            'dates' => new Column(['title' => __('models/importInstallations.fields.dates'), 'data' => 'dates',
            'render' => 'function() {
                const dateObject = new Date(full.dates);
                if (isNaN(dateObject.getTime())) {
                    return data; 
                }
                const year = dateObject.getFullYear();
                const month = String(dateObject.getMonth() + 1).padStart(2, "0");
                const day = String(dateObject.getDate()).padStart(2, "0");
                const hours = String(dateObject.getHours()).padStart(2, "0");
                const minutes = String(dateObject.getMinutes()).padStart(2, "0");
                const formattedDate = `${day}/${month}/${year}`;
                return formattedDate;
            }',

            ])
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'import_installations_datatable_' . time();
    }
}
