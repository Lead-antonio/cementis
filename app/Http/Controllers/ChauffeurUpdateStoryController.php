<?php

namespace App\Http\Controllers;

use App\DataTables\ChauffeurUpdateStoryDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateChauffeurUpdateStoryRequest;
use App\Http\Requests\UpdateChauffeurUpdateStoryRequest;
use App\Repositories\ChauffeurUpdateStoryRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Mail\ChauffeurUpdateMail;
use App\Models\ChauffeurUpdateStory;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Response;

class ChauffeurUpdateStoryController extends AppBaseController
{
    /** @var ChauffeurUpdateStoryRepository $chauffeurUpdateStoryRepository*/
    private $chauffeurUpdateStoryRepository;

    public function __construct(ChauffeurUpdateStoryRepository $chauffeurUpdateStoryRepo)
    {
        $this->chauffeurUpdateStoryRepository = $chauffeurUpdateStoryRepo;
    }

    /**
     * Display a listing of the ChauffeurUpdateStory.
     *
     * @param ChauffeurUpdateStoryDataTable $chauffeurUpdateStoryDataTable
     *
     * @return Response
     */
    public function index(ChauffeurUpdateStoryDataTable $chauffeurUpdateStoryDataTable)
    {
        return $chauffeurUpdateStoryDataTable->render('chauffeur_update_stories.index');
    }

    /**
     * Show the form for creating a new ChauffeurUpdateStory.
     *
     * @return Response
     */
    public function create()
    {
        return view('chauffeur_update_stories.create');
    }

    /**
     * Store a newly created ChauffeurUpdateStory in storage.
     *
     * @param CreateChauffeurUpdateStoryRequest $request
     *
     * @return Response
     */
    public function store(CreateChauffeurUpdateStoryRequest $request)
    {
        $input = $request->all();

        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->create($input);

        $chauffeur_info = ChauffeurUpdateStory::where('id',$chauffeurUpdateStory->id)
        ->with(['chauffeur','chauffeur.related_transporteur','chauffeur_update_type','transporteur'])->get()->toArray();
                
        Mail::to("harilovajohnny@gmail.com") // Remplacez par l'email du destinataire
        ->send(new ChauffeurUpdateMail($chauffeur_info));   

         Alert::success(__('messages.saved', ['model' => __('models/chauffeurs.singular')]));
        
        return redirect(route('chauffeurs.index'));
    }

    /**
     * Display the specified ChauffeurUpdateStory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->find($id);

        if (empty($chauffeurUpdateStory)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateStories.singular')]));

            return redirect(route('chauffeurUpdateStories.index'));
        }

        return view('chauffeur_update_stories.show')->with('chauffeurUpdateStory', $chauffeurUpdateStory);
    }

    /**
     * Show the form for editing the specified ChauffeurUpdateStory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->find($id);

        if (empty($chauffeurUpdateStory)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateStories.singular')]));

            return redirect(route('chauffeurUpdateStories.index'));
        }

        return view('chauffeur_update_stories.edit')->with('chauffeurUpdateStory', $chauffeurUpdateStory);
    }

    /**
     * Update the specified ChauffeurUpdateStory in storage.
     *
     * @param int $id
     * @param UpdateChauffeurUpdateStoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChauffeurUpdateStoryRequest $request)
    {
        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->find($id);

        if (empty($chauffeurUpdateStory)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateStories.singular')]));

            return redirect(route('chauffeurUpdateStories.index'));
        }

        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/chauffeurUpdateStories.singular')]));

        return redirect(route('chauffeurUpdateStories.index'));
    }

    /**
     * Remove the specified ChauffeurUpdateStory from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $chauffeurUpdateStory = $this->chauffeurUpdateStoryRepository->find($id);

        if (empty($chauffeurUpdateStory)) {
            Flash::error(__('messages.not_found', ['model' => __('models/chauffeurUpdateStories.singular')]));

            return redirect(route('chauffeurUpdateStories.index'));
        }

        $this->chauffeurUpdateStoryRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/chauffeurUpdateStories.singular')]));

        return redirect(route('chauffeurUpdateStories.index'));
    }
}
