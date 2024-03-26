<?php

namespace App\Http\Controllers;

use App\DataTables\FichierExcelDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateFichierExcelRequest;
use App\Http\Requests\UpdateFichierExcelRequest;
use App\Repositories\FichierExcelRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\FichierExcel;

use Response;

class FichierExcelController extends AppBaseController
{
    /** @var FichierExcelRepository $fichierExcelRepository*/
    private $fichierExcelRepository;

    public function __construct(FichierExcelRepository $fichierExcelRepo)
    {
        $this->fichierExcelRepository = $fichierExcelRepo;
    }

    /**
     * Display a listing of the FichierExcel.
     *
     * @param FichierExcelDataTable $fichierExcelDataTable
     *
     * @return Response
     */
    public function index(FichierExcelDataTable $fichierExcelDataTable)
    {
        return $fichierExcelDataTable->render('fichier_excels.index');
    }

    /**
     * Show the form for creating a new FichierExcel.
     *
     * @return Response
     */
    public function create()
    {
        return view('fichier_excels.create');
    }

    /**
     * Store a newly created FichierExcel in storage.
     *
     * @param CreateFichierExcelRequest $request
     *
     * @return Response
     */
    public function store(CreateFichierExcelRequest $request)
    {
        $input = $request->all();

        $fichierExcel = $this->fichierExcelRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/fichierExcels.singular')]));

        return redirect(route('fichierExcels.index'));
    }

    /**
     * Display the specified FichierExcel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $fichierExcel = $this->fichierExcelRepository->find($id);

        if (empty($fichierExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fichierExcels.singular')]));

            return redirect(route('fichierExcels.index'));
        }

        return view('fichier_excels.show')->with('fichierExcel', $fichierExcel);
    }

    /**
     * Show the form for editing the specified FichierExcel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $fichierExcel = $this->fichierExcelRepository->find($id);

        if (empty($fichierExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fichierExcels.singular')]));

            return redirect(route('fichierExcels.index'));
        }

        return view('fichier_excels.edit')->with('fichierExcel', $fichierExcel);
    }

    /**
     * Update the specified FichierExcel in storage.
     *
     * @param int $id
     * @param UpdateFichierExcelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFichierExcelRequest $request)
    {
        $fichierExcel = $this->fichierExcelRepository->find($id);

        if (empty($fichierExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fichierExcels.singular')]));

            return redirect(route('fichierExcels.index'));
        }

        $fichierExcel = $this->fichierExcelRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/fichierExcels.singular')]));

        return redirect(route('fichierExcels.index'));
    }

    /**
     * Remove the specified FichierExcel from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $fichierExcel = $this->fichierExcelRepository->find($id);

        if (empty($fichierExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fichierExcels.singular')]));

            return redirect(route('fichierExcels.index'));
        }

        $this->fichierExcelRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/fichierExcels.singular')]));

        return redirect(route('fichierExcels.index'));
    }


    /**
     * redirection vers affichage import excel.
     *
     * @return Response
     */
    public function affichage_import()
    {
        return view('fichier_excels.create');
    }


    /**
     * Importation fichier excel
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import_excel(Request $request)
    {
        // recuperation de la date d'importation du fichier excel
        $maintenant = Carbon::now();
        $dateFormatee = $maintenant->format('d-m-Y');
       
        // Recupération du nom de l'utilisateur connecté
        $user = Auth::user();

        // Concatenation du nom de l'user connecté avec la date importation 
        $name_file_excel = $user->name ."-".$dateFormatee;
        

        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);


        $fichier_excels = new FichierExcel([
            'name' => $name_file_excel
        ]);
        $fichier_excels->save();

        $file = $request->file('excel_file');

        // Excel::import(new YourImportClass, $file);
        
        Flash::success(__('Importation reussie'));

        return redirect(route('fichierExcels.index'));
    }

}
