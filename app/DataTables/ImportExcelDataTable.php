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
        $import_calendar_id = request()->route('id');

        if($import_calendar_id ==null ){
            return $model->newQuery();
        }else{
             // Filtrer les données par import_calendar_id
            return $model->newQuery()->where('import_calendar_id', $import_calendar_id);
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
            ->addAction(['width' => '120px', 'printable' => false, 'title' => __('crud.action')])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    'excel', 'csv', 'print',
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
            'name_importation' => new Column(['title' => __('models/importExcels.fields.name_importation'), 'data' => 'name_importation']),
            // 'rfid_chauffeur' => new Column(['title' => __('models/importExcels.fields.rfid_chauffeur'), 'data' => 'rfid_chauffeur']),
            'camion' => new Column(['title' => __('models/importExcels.fields.camion'), 'data' => 'camion']),
            'badge_chauffeur' => new Column(['title' => __('models/importExcels.fields.badge_chauffeur'), 'data' => 'badge_chauffeur']),
            'date_debut' => new Column([
                'title' => __('models/importExcels.fields.date_debut'), 'data' => 'date_debut',
                'render' => 'function() {
                    var date = new Date(full.date_debut);
                    date.setHours(date.getHours() - 3);
                    var date_heure = moment(date).format("DD-MM-YYYY HH:mm:ss");
                    return full.date_debut;
                }',
            ]),
            'date_fin' => new Column(['title' => __('models/importExcels.fields.date_fin'), 'data' => 'date_fin',
            'render' => 'function() {
                var date_heure = "";

                if(full.date_fin!=null){
                    var date = new Date(full.date_fin);
                    date.setHours(date.getHours() - 3);
                    var date_heure = moment(date).format("DD-MM-YYYY HH:mm:ss");
                }
                return full.date_fin;}'
        
        ]),
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
