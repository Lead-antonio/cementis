<?php

namespace App\Http\Controllers;

use App\DataTables\ParametreDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateParametreRequest;
use App\Http\Requests\UpdateParametreRequest;
use App\Repositories\ParametreRepository;
use GuzzleHttp\Client;
use App\Models\Parametre;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ParametreController extends AppBaseController
{
    /** @var ParametreRepository $parametreRepository*/
    private $parametreRepository;

    public function __construct(ParametreRepository $parametreRepo)
    {
        $this->parametreRepository = $parametreRepo;
    }

    /**
     * Display a listing of the Parametre.
     *
     * @param ParametreDataTable $parametreDataTable
     *
     * @return Response
     */
    public function index(ParametreDataTable $parametreDataTable)
    {
        $client = new Client();

        // Spécifiez l'URL de l'API que vous souhaitez interroger
        $apiUrl = 'www.m-tectracking.mg/api/api.php?api=user&ver=1.0&key=0AFEAB2328492FB8118E37ECCAF5E79F&cmd=USER_GET_ZONES';
 
        // Faites la requête GET à l'API
        $response = $client->get($apiUrl);

         // Obtenez le contenu de la réponse
        $data = json_decode($response->getBody()->getContents(), true);
        // Vérifiez si des données ont été récupérées
        if (!empty($data)) {
            foreach ($data as $item) {
                // Vérifiez si une entrée identique existe déjà dans la table Rotation
                $existingParametre = Parametre::where('name', $item['name'])
                ->first();
                // Si aucune entrée identique n'existe, insérez les données dans la table Rotation
                if (!$existingParametre) {
                    Parametre::create([
                        'name' => $item['name'],
                        'color' => $item['color'],
                        'limite' => null,
                    ]);
                }
            }
        }

        return $parametreDataTable->render('parametres.index');
    }

    /**
     * Show the form for creating a new Parametre.
     *
     * @return Response
     */
    public function create()
    {
        return view('parametres.create');
    }

    /**
     * Store a newly created Parametre in storage.
     *
     * @param CreateParametreRequest $request
     *
     * @return Response
     */
    public function store(CreateParametreRequest $request)
    {
        $input = $request->all();

        $parametre = $this->parametreRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/parametres.singular')]));

        return redirect(route('parametres.index'));
    }

    /**
     * Display the specified Parametre.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $parametre = $this->parametreRepository->find($id);

        if (empty($parametre)) {
            Flash::error(__('messages.not_found', ['model' => __('models/parametres.singular')]));

            return redirect(route('parametres.index'));
        }

        return view('parametres.show')->with('parametre', $parametre);
    }

    /**
     * Show the form for editing the specified Parametre.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $parametre = $this->parametreRepository->find($id);

        if (empty($parametre)) {
            Flash::error(__('messages.not_found', ['model' => __('models/parametres.singular')]));

            return redirect(route('parametres.index'));
        }

        return view('parametres.edit')->with('parametre', $parametre);
    }

    /**
     * Update the specified Parametre in storage.
     *
     * @param int $id
     * @param UpdateParametreRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateParametreRequest $request)
    {
        $parametre = $this->parametreRepository->find($id);

        if (empty($parametre)) {
            Flash::error(__('messages.not_found', ['model' => __('models/parametres.singular')]));

            return redirect(route('parametres.index'));
        }

        $parametre = $this->parametreRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/parametres.singular')]));

        return redirect(route('parametres.index'));
    }

    /**
     * Remove the specified Parametre from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $parametre = $this->parametreRepository->find($id);

        if (empty($parametre)) {
            Flash::error(__('messages.not_found', ['model' => __('models/parametres.singular')]));

            return redirect(route('parametres.index'));
        }

        $this->parametreRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/parametres.singular')]));

        return redirect(route('parametres.index'));
    }
}
