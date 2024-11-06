<?php

namespace App\Http\Controllers;

use App\Exports\DynamicTableExport;
use App\Models\Chauffeur;
use App\Models\Infraction;
use App\Models\Transporteur;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends AppBaseController
{

     // Lister les modèles disponibles
     protected $models = [
        'users' => User::class,
        'Transporteur' => Transporteur::class,
        'Infraction' => Infraction::class,
        'chauffeur' => Chauffeur::class,
        'vehicule' => Vehicule::class,
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


     /**
     * affichage exportation generique
     *
     * @return Response
     */
    public function exportation_excel()
    {
        $modelNames = array_keys($this->models); // Récupérer les noms des modèles pour l'interface
        return view('export_general.export_generique', compact('modelNames'));
    }


    public function getTableColumns(Request $request)
    {
        $modelName = $request->input('model');
        $modelClass = $this->models[$modelName] ?? null;

        if ($modelClass) {
            $table = (new $modelClass)->getTable();
            $columns = Schema::getColumnListing($table);
            return response()->json($columns);
        }

        return response()->json([], 404); // Si le modèle n'existe pas
    }

    /**
     * Summary of exportTable
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportTable(Request $request)
    {
        $table = $request->input('table');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        return Excel::download(new DynamicTableExport($table, $startDate, $endDate), $table . '.xlsx');
    }

}
