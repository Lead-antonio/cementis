<?php

namespace App\Http\Controllers;

use App\DataTables\ImportExcelDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateImportExcelRequest;
use App\Http\Requests\UpdateImportExcelRequest;
use App\Repositories\ImportExcelRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Imports\ExcelImportClass;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ImportExcel;
use Illuminate\Support\Facades\Storage;

class ImportExcelController extends AppBaseController
{
    /** @var ImportExcelRepository $importExcelRepository*/
    private $importExcelRepository;

    public function __construct(ImportExcelRepository $importExcelRepo)
    {
        $this->importExcelRepository = $importExcelRepo;
    }

    /**
     * Display a listing of the ImportExcel.
     *
     * @param ImportExcelDataTable $importExcelDataTable
     *
     * @return Response
     */
    public function index(ImportExcelDataTable $importExcelDataTable)
    {
        return $importExcelDataTable->render('import_excels.index');
    }

    /**
     * Show the form for creating a new ImportExcel.
     *
     * @return Response
     */
    public function create()
    {
        return view('import_excels.create');
    }

    /**
     * Store a newly created ImportExcel in storage.
     *
     * @param CreateImportExcelRequest $request
     *
     * @return Response
     */
    public function store(CreateImportExcelRequest $request)
    {
        $input = $request->all();

        $importExcel = $this->importExcelRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/importExcels.singular')]));

        return redirect(route('importExcels.index'));
    }

    /**
     * Display the specified ImportExcel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $importExcel = $this->importExcelRepository->find($id);

        if (empty($importExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importExcels.singular')]));

            return redirect(route('importExcels.index'));
        }

        return view('import_excels.show')->with('importExcel', $importExcel);
    }

    /**
     * Show the form for editing the specified ImportExcel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $importExcel = $this->importExcelRepository->find($id);

        if (empty($importExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importExcels.singular')]));

            return redirect(route('importExcels.index'));
        }

        return view('import_excels.edit')->with('importExcel', $importExcel);
    }

    /**
     * Update the specified ImportExcel in storage.
     *
     * @param int $id
     * @param UpdateImportExcelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImportExcelRequest $request)
    {
        $importExcel = $this->importExcelRepository->find($id);

        if (empty($importExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importExcels.singular')]));

            return redirect(route('importExcels.index'));
        }

        $importExcel = $this->importExcelRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/importExcels.singular')]));

        return redirect(route('importExcels.index'));
    }

    /**
     * Remove the specified ImportExcel from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $importExcel = $this->importExcelRepository->find($id);

        if (empty($importExcel)) {
            Flash::error(__('messages.not_found', ['model' => __('models/importExcels.singular')]));

            return redirect(route('importExcels.index'));
        }

        $this->importExcelRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/importExcels.singular')]));

        return redirect(route('importExcels.index'));
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
        $dateFormatee = $maintenant->format('d-m-Y H:i');

        // Recupération du nom de l'utilisateur connecté
        $user = Auth::user();

        // Concatenation du nom de l'user connecté avec la date importation 
        $name_file_excel = $user->name ."-".$dateFormatee;

        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);


        $file = $request->file('excel_file');

        // dd($file);

        Excel::import(new ExcelImportClass, $file);
        
        Flash::success(__('Importation reussie'));

        return redirect(route('fichierExcels.index'));
    }


}
