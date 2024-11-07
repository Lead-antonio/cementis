<?php

namespace App\Http\Controllers;

use App\Exports\DynamicTableExport;
use App\Models\Chauffeur;
use App\Models\Infraction;
use App\Models\Scoring;
use App\Models\Transporteur;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends AppBaseController
{

     // Lister les modèles disponibles
     protected $models = [
        'Infraction' => Infraction::class,
        'scoring' => Scoring::class,
    ];


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


    // public function getTableColumns(Request $request)
    // {
    //     $modelName = $request->input('model');
    //     $modelClass = $this->models[$modelName] ?? null;

    //     if ($modelClass) {
    //         try {
    //             $table = (new $modelClass)->getTable();

    //             // Colonnes spécifiées pour certains modèles
    //             $restrictedColumns = [
    //                 'Infraction' => ['date_debut', 'date_fin'],
    //                 // Ajouter d'autres modèles et leurs colonnes restreintes ici si nécessaire
    //             ];

    //             // Utiliser les colonnes restreintes si le modèle a des restrictions
    //             $columns = $restrictedColumns[$modelName] ?? Schema::getColumnListing($table);

    //             return response()->json($columns);
    //         } catch (\Exception $e) {
    //             return response()->json(['error' => 'Erreur lors de la récupération des colonnes : ' . $e->getMessage()], 500);
    //         }
    //     }

    //     return response()->json(['error' => 'Modèle non trouvé ou invalide'], 404);
    // }


    public function getTableColumns(Request $request)
    {
        $modelName = $request->input('model');
        $modelClass = $this->models[$modelName] ?? null;

        if ($modelClass) {
            $table = (new $modelClass)->getTable();

            // Colonnes restreintes pour les filtres
            $restrictedColumns = [
                'Infraction' => ['date_debut', 'date_fin'],
                // Ajoutez des restrictions pour d'autres modèles si nécessaire
            ];

            // Obtenir toutes les colonnes de la table pour l'affichage complet
            $allColumns = Schema::getColumnListing($table);

            // Filtrer uniquement les colonnes spécifiées pour le modèle (pour le filtre)
            $filterableColumns = $restrictedColumns[$modelName] ?? $allColumns;

            // Ajouter les types de colonnes pour toutes les colonnes
            $columnsWithTypes = [];
            foreach ($allColumns as $column) {
                $type = Schema::getColumnType($table, $column);
                $columnsWithTypes[] = [
                    'name' => $column,
                    'type' => $type,
                    'filterable' => in_array($column, $filterableColumns), // Indique si c'est filtrable
                ];
            }

            return response()->json($columnsWithTypes);
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
        $filters = json_decode($request->input('filters'), true);
    
        // Vérifier que la table existe
        if (!Schema::hasTable($table)) {
            return back()->withErrors('Table introuvable');
        }
    
        $query = DB::table($table);
    
        // Appliquer les filtres dynamiques
        if (!empty($filters)) {
            $query->where(function ($query) use ($filters) {
                foreach ($filters as $filter) {
                    if (isset($filter['column'], $filter['operator'], $filter['value'])) {
                        if (isset($filter['connector']) && $filter['connector'] === 'OR') {
                            $query->orWhere($filter['column'], $filter['operator'], $filter['value']);
                        } else {
                            $query->where($filter['column'], $filter['operator'], $filter['value']);
                        }
                    }
                }
            });
        }
    
        // Exécution de la requête et exportation
        $results = $query->get();
    
        // Exportation avec Excel
        return Excel::download(new DynamicTableExport($query->get(), $table), $table . '.xlsx');
    }
    

}
