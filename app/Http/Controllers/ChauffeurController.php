<?php

namespace App\Http\Controllers;

use App\DataTables\ChauffeurDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateChauffeurRequest;
use App\Http\Requests\UpdateChauffeurRequest;
use App\Models\Transporteur;
use App\Repositories\ChauffeurRepository;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Imports\DriverImportClass;
use App\Models\Chauffeur;
use App\Models\ChauffeurUpdate;
use App\Models\ChauffeurUpdateType;
use Excel;
use Response;

class ChauffeurController extends AppBaseController
{
    /** @var ChauffeurRepository $chauffeurRepository*/
    private $chauffeurRepository;

    public function __construct(ChauffeurRepository $chauffeurRepo)
    {
        $this->chauffeurRepository = $chauffeurRepo;
    }

    /**
     * Display a listing of the Chauffeur.
     *
     * @param ChauffeurDataTable $chauffeurDataTable
     *
     * @return Response
     */
    public function index(ChauffeurDataTable $chauffeurDataTable,  Request $request)
    {
        $query = Chauffeur::query()->with(['related_transporteur', 'chauffeur_update']);

        // Si le paramètre 'non_fixe' est présent, filtre les chauffeurs
        if ($request->input('non_fixe') == 1) {
            $query->where('chauffeur.nom', 'chauffeur non fixe');
        }
        
        if(Session::has('success')){
            Alert::success(__('messages.saved', ['model' => __('models/chauffeurs.singular')]));
            Session::forget('success');
        }

        if(Session::has('updated')){
            Alert::success(__('messages.updated', ['model' => __('models/chauffeurs.singular')]));
            Session::forget('updated');
        }

        if(Session::has('deleted')){
            Alert::success(__('messages.deleted', ['model' => __('models/chauffeurs.singular')]));
            Session::forget('deleted');
        }
        // return $chauffeurDataTable->render('chauffeurs.index');
        return $chauffeurDataTable->withQuery($query)->render('chauffeurs.index');
    }

    /**
     * Show the form for creating a new Chauffeur.
     *
     * @return Response
     */
    public function create()
    {
        $transporteur = Transporteur::all();
        $action = "create";
        return view('chauffeurs.create', compact('transporteur', 'action'));
    }
    
    public function import_driver_excel(Request $request){
        try{
            // Verfication de l'extension du fichier 
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls'
            ]);
            $file = $request->file('excel_file'); 
            
            // Ajout du nom du fichier importer 
            $import = new DriverImportClass();
            Excel::import($import, $file);
            Alert::success(__('Importation réussie'));
            return redirect(route('chauffeurs.index'));
        }catch(\Exception $e){
            Alert::error(__('Erreur lors de l\'importation du fichier'));
        }
    }

    /**
     * Store a newly created Chauffeur in storage.
     *
     * @param CreateChauffeurRequest $request
     *
     * @return Response
     */
    public function store(CreateChauffeurRequest $request)
    {
        $input = $request->all();

        $chauffeur = $this->chauffeurRepository->create($input);

        Alert::success(__('messages.saved', ['model' => __('models/chauffeurs.singular')]));
        // Session::put('success', 'success');

        return redirect(route('chauffeurs.index'));
    }

    /**
     * Display the specified Chauffeur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $chauffeur = $this->chauffeurRepository->find($id);
        $chauffeur_update = ChauffeurUpdate::with('transporteur')->where('chauffeur_id',$id)->get();
        if (empty($chauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

            return redirect(route('chauffeurs.index'));
        }

        return view('chauffeurs.show',compact('chauffeur','chauffeur_update'));
    }

    /**
     * Show the form for editing the specified Chauffeur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $chauffeur = $this->chauffeurRepository->find($id);
        $transporteur = Transporteur::find($chauffeur->transporteur_id);
        $action = "edit";
        if (empty($chauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

            return redirect(route('chauffeurs.index'));
        }

        return view('chauffeurs.edit', compact('chauffeur', 'transporteur','action'));
    }

    /**
     * Show the form for editing the specified Chauffeur.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit_story($id)
    {
        $chauffeur = $this->chauffeurRepository->find($id);
        $transporteur = Transporteur::pluck('nom', 'id');
        $action = "edit";
        $chauffeurUpdateTypes = ChauffeurUpdateType::pluck('name', 'id'); // Récupère les types d'update

        
        // if (empty($chauffeur)) {
        //     Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

        //     return redirect(route('chauffeurs.index'));
        // }

        return view('chauffeurs.edit_story', compact('chauffeur', 'transporteur','chauffeurUpdateTypes','action'));
    }

    /**
     * Update the specified Chauffeur in storage.
     *
     * @param int $id
     * @param UpdateChauffeurRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChauffeurRequest $request)
    {
        $chauffeur = $this->chauffeurRepository->find($id);

        if (empty($chauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

            return redirect(route('chauffeurs.index'));
        }

        $chauffeur = $this->chauffeurRepository->update($request->all(), $id);

        // Alert::success(__('messages.updated', ['model' => __('models/chauffeurs.singular')]));
        Session::put('updated', 'updated');

        return redirect(route('chauffeurs.index'));
    }


    /**
     * Update the specified Chauffeur in storage.
     *
     *
     * @return Response
     */
    public function update_tranporteur_id(Request $request)
    {

        $transporteur_id = $request->transporteur_id;
        $chauffeur_id = $request->chauffeur;
        $chauffeurIdsInt = array_map('intval', $chauffeur_id);
        
        Chauffeur::whereIn('id',$chauffeurIdsInt)->update(['transporteur_id'=>$transporteur_id]);

        Alert::success(__('messages.updated', ['model' => __('models/clients.singular')]));

        return redirect(route('chauffeurs.index'));
    }

    /**
     * Remove the specified Chauffeur from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $chauffeur = $this->chauffeurRepository->find($id);

        if (empty($chauffeur)) {
            Alert::error(__('messages.not_found', ['model' => __('models/chauffeurs.singular')]));

            return redirect(route('chauffeurs.index'));
        }

        $this->chauffeurRepository->delete($id);

        // Alert::success(__('messages.deleted', ['model' => __('models/chauffeurs.singular')]));
        Session::put('deleted', 'deleted');

        return redirect(route('chauffeurs.index'));
    }
}
