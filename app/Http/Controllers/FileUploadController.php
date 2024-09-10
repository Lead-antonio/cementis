<?php

namespace App\Http\Controllers;

use App\DataTables\FileUploadDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateFileUploadRequest;
use App\Http\Requests\UpdateFileUploadRequest;
use App\Imports\ModelImportClass;
use App\Models\FileUpload;
use App\Models\ImportModel;
use App\Repositories\FileUploadRepository;
use App\Repositories\ImportModelRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class FileUploadController extends AppBaseController
{
    /** @var  FileUploadRepository */
    private $fileUploadRepository;
    private $importModelRepository;

    public function __construct(FileUploadRepository $fileUploadRepo, ImportModelRepository $importModelRepo)
    {
        $this->fileUploadRepository = $fileUploadRepo;
        $this->importModelRepository = $importModelRepo;
    }

    /**
     * Display a listing of the FileUpload.
     *
     * @param FileUploadDataTable $fileUploadDataTable
     * @return Response
     */
    public function index(FileUploadDataTable $fileUploadDataTable)
    {
        return $fileUploadDataTable->render('file_uploads.index');
    }

    // public function parametre(Request $request)
    // {
    //     $data = json_decode($request->input('data'), true);
    //     return view('file_uploads.parametre', compact('data'));
    // }

    public function parametre()
    {
        $fileName = session()->get('fileName');
        $filePath = session()->get('filePath');
        $data = session()->get('data');

        $lastId = ImportModel::max('id') ?? 0;
        $newId = $lastId + 1;

        return view('file_uploads.parametre', compact('filePath', 'fileName', 'data', 'newId'));
    }

    // affiché les colonnes de la table
    public function getFillableFields($model)
    {
        // Construct the fully qualified model class name
        $modelClass = '\\App\\Models\\' . $model;

        // Ensure the model class exists
        if (class_exists($modelClass)) {
            $fillable = (new $modelClass)->getFillable();
            return response()->json($fillable);
        }

        return response()->json([]);
    }

    // affiché les noms des models
    public function getModels($model)
    {
        // Remplacez ce code par la logique pour obtenir les attributs de la table import_model
        $models = ImportModel::where('model', $model)->pluck('nom', 'id')->toArray();

        return response()->json($models);
    }

    public function getAssociations($modelId)
    {
        // Obtenez l'import_model par ID
        $importModel = ImportModel::find($modelId);

        // Vérifiez si l'association est un tableau
        if (is_array($importModel->association)) {
            $associations = $importModel->association;
        } else {
            // Si ce n'est pas un tableau, décodez la chaîne JSON
            $associations = json_decode($importModel->association, true);
        }

        return response()->json($associations);
    }


    public function read(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            $file = $request->file('excel_file');

            // Lire le contenu du fichier Excel
            $data = Excel::toArray([], $file);

            // Stocker le fichier
            $name = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $name, 'public');
            $fileName = $file->getClientOriginalName();

            $verification = FileUpload::where('name', $fileName)->first();

            if ($verification != null) {
                // Stocker un message d'erreur dans la session
                session()->flash('error_message', 'Le fichier a déjà été importé.');

                return response()->json([
                    'redirect_url' => route('fileUploads.create')
                ]);
            }

            // Stocker les informations dans la session
            session()->put('filePath', $filePath);
            session()->put('fileName', $fileName);
            session()->put('data', $data);

            // URL de redirection
            $redirectUrl = route('fileUploads.parametre');

            // Réponse JSON avec l'URL de redirection
            return response()->json([
                'redirect_url' => $redirectUrl
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }



    /**
     * Show the form for creating a new FileUpload.
     *
     * @return Response
     */
    public function create()
    {
        // Récupérer le message d'erreur de la session
        $errorMessage = session('error_message');

        return view('file_uploads.create', compact('errorMessage'));
    }


    /**
     * Store a newly created FileUpload in storage.
     *
     * @param CreateFileUploadRequest $request
     *
     * @return Response
     */
    public function store(CreateFileUploadRequest $request)
    {
        $input = $request->all();

        // Vérifiez si index_map est défini
        if (!isset($input['index_map'])) {
            // Stocker un message d'erreur dans la session
            session()->flash('error_message', 'Veuillez choisir une table!');

            // Redirigez vers la page précédente
            return redirect()->back();
        }

        // Construire l'indexMap à partir des données envoyées
        $indexMap = array_filter($input['index_map'] ?? [], function ($value) {
            return $value !== null;
        });

        // Vérifiez si indexMap est vide après le filtrage
        if (empty($indexMap)) {
            // Stocker un message d'erreur dans la session
            session()->flash('error_message', 'Veuillez associer les colonnes!');

            // Redirigez vers la page précédente
            return redirect()->back();
        }

        // insertion dans import_model
        if (isset($input['choixSauver'])) {
            // Assigner les valeurs du formulaire
            $input['association'] = $indexMap;
            $input['model'] = $input['option'];

            // // Obtenir le dernier ID inséré avant l'insertion
            // $lastId = ImportModel::max('id') ?? 0;

            // // Incrémenter l'ID de 1 pour le nouveau nom
            // $newId = $lastId + 1;

            // // Structurer le nom
            // $input['nom'] = $input['option'] . ' Importation Model ' . $newId;

            // Créer l'entrée dans la base de données avec le nom structuré
            $importModel = $this->importModelRepository->create($input);
        }


        // Récupérer la valeur du champ excel_file
        $excelFilePath = $input['excel_file'] ?? null;

        // Obtenir le chemin complet du fichier stocké dans le répertoire public
        $fullPath = storage_path('app/public/' . $excelFilePath);

        // Vérifiez si le fichier existe
        /* if (!file_exists($fullPath)) {
            Flash::error(__('messages.not.found', ['model' => __('models/fileUploads.singular')]));

            return redirect(route('fileUploads.index'));
        } */



        $modelClass = '\\App\\Models\\' . $input['option'];

        $import = new ModelImportClass($indexMap, $modelClass);
        Excel::import($import, $fullPath);

        
        // Insertion automatique du mouvement pour Pointage
        // if ($input['option'] === 'Pointage') {
        //     // Étape 2 : Récupérer la date la plus récente dans la table 'pointage'
        //     $latestDate = \DB::table('pointage')
        //         ->select('date')
        //         ->orderBy('date', 'desc')
        //         ->first();

        //     if ($latestDate) {
        //         // Étape 3 : Trouver tous les enregistrements pour cette date
        //         $pointages = Pointage::where('date', $latestDate->date)->get();

        //         // Étape 4 : Grouper par 'num_pointage' et compter les occurrences
        //         $groupedPointages = $pointages->groupBy('num_pointage');

        //         foreach ($groupedPointages as $numPointage => $pointageGroup) {
        //             foreach ($pointageGroup as $index => $pointage) {
        //                 // Étape 5 : Mettre à jour 'move' en fonction de la parité de l'index
        //                 $pointage->move = ($index % 2 === 0) ? 'in' : 'out';
        //                 $pointage->save();
        //             }
        //         }
        //     }
        // }


        // Obtenez le nombre de lignes insérées
        $rowCount = $import->getRowCount();

        // insertion dans file_upload

        // Ajouter la valeur à un autre champ ou utiliser comme nécessaire
        $input['file_upload'] = $excelFilePath;

        // Créer l'entrée dans la base de données
        $fileUpload = $this->fileUploadRepository->create($input);

        // Message flash avec le nombre de lignes insérées
        Flash::success("Importation réussie : $rowCount lignes insérée!");


        return redirect(route('fileUploads.index'));
    }



    /**
     * Display the specified FileUpload.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $fileUpload = $this->fileUploadRepository->find($id);

        if (empty($fileUpload)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fileUploads.singular')]));

            return redirect(route('fileUploads.index'));
        }

        return view('file_uploads.show')->with('fileUpload', $fileUpload);
    }

    /**
     * Show the form for editing the specified FileUpload.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $fileUpload = $this->fileUploadRepository->find($id);

        if (empty($fileUpload)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fileUploads.singular')]));

            return redirect(route('fileUploads.index'));
        }

        return view('file_uploads.edit')->with('fileUpload', $fileUpload);
    }

    /**
     * Update the specified FileUpload in storage.
     *
     * @param  int              $id
     * @param UpdateFileUploadRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFileUploadRequest $request)
    {
        $fileUpload = $this->fileUploadRepository->find($id);

        if (empty($fileUpload)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fileUploads.singular')]));

            return redirect(route('fileUploads.index'));
        }
        $input = $request->all();
        if ($request->hasfile('excel_file')) {
            $file_upload = $request->file('excel_file');
            $name = time() . '_' . $file_upload->getClientOriginalName();
            $filePath = $file_upload->storeAs('uploads', $name, 'public');
            $input['file_upload'] = $filePath;
        }
        $fileUpload = $this->fileUploadRepository->update($input, $id);

        Flash::success(__('messages.updated', ['model' => __('models/fileUploads.singular')]));

        return redirect(route('fileUploads.index'));
    }

    /**
     * Remove the specified FileUpload from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $fileUpload = $this->fileUploadRepository->find($id);

        if (empty($fileUpload)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fileUploads.singular')]));

            return redirect(route('fileUploads.index'));
        }

        $this->fileUploadRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/fileUploads.singular')]));

        return redirect(route('fileUploads.index'));
    }
}
