<?php

namespace App\Http\Controllers;

use App\DataTables\RotationDataTable;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests;
use App\Http\Requests\CreateRotationRequest;
use App\Http\Requests\UpdateRotationRequest;
use Carbon\Carbon;
use App\Repositories\RotationRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Rotation;
use GuzzleHttp\Client;
use Carbon\CarbonInterval;
use Response;

class RotationController extends AppBaseController
{
    /** @var RotationRepository $rotationRepository*/
    private $rotationRepository;

    public function __construct(RotationRepository $rotationRepo)
    {
        $this->rotationRepository = $rotationRepo;
    }

    /**
     * Display a listing of the Rotation.
     *
     * @param RotationDataTable $rotationDataTable
     *
     * @return Response
     */
    public function index(RotationDataTable $rotationDataTable){
        $client = new Client();
        $info = $this->getRotationDurations();

         // Spécifiez l'URL de l'API que vous souhaitez interroger
         $apiUrl = 'www.m-tectracking.mg/api/api.php?api=user&key=0AFEAB2328492FB8118E37ECCAF5E79F&cmd=OBJECT_GET_LAST_EVENTS_7D';
 
         // Faites la requête GET à l'API
         $response = $client->get($apiUrl);

         // Obtenez le contenu de la réponse
        $data = json_decode($response->getBody()->getContents(), true);
        // Vérifiez si des données ont été récupérées
        if (!empty($data)) {
            foreach ($data as $item) {
                // Vérifiez si une entrée identique existe déjà dans la table Rotation
                $existingRotation = Rotation::where('imei', $item[2])
                ->where('date_heure', $item[4])
                ->first();
                // Si aucune entrée identique n'existe, insérez les données dans la table Rotation
                if (!$existingRotation) {
                    Rotation::create([
                        'imei' => $item[2],
                        'type' => $item[0],
                        'description' => $item[1],
                        'vehicule' => $item[3],
                        'date_heure' => $item[4],
                        'latitude' => $item[5],
                        'longitude' => $item[6],
                    ]);
                }
            }
        }
        
        return $rotationDataTable->render('rotations.index', compact('info'));
    }

    public function getDataFromApi(){
         // Créez une instance de Guzzle
         $client = new Client();

         // Spécifiez l'URL de l'API que vous souhaitez interroger
         $apiUrl = 'www.m-tectracking.mg/api/api.php?api=user&key=0AFEAB2328492FB8118E37ECCAF5E79F&cmd=OBJECT_GET_LAST_EVENTS_7D';
 
         // Faites la requête GET à l'API
         $response = $client->get($apiUrl);

         // Obtenez le contenu de la réponse
        $data = json_decode($response->getBody()->getContents(), true);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        $currentPageSearchResults = array_slice($data, ($currentPage - 1) * $perPage, $perPage);

        $data = new LengthAwarePaginator($currentPageSearchResults, count($data), $perPage);
        $data->withPath(route('get.data.api')); 

        return view('data.index', ['data' => $data]);
    }

    public function getRotationDurations(){
         // Sélectionnez toutes les rotations pour le véhicule donné
        $rotations = Rotation::where('vehicule', "=" ,"3695TAH")->get();

        $rotationDurationsDetail = [];
        $totalDuration = 0;

        // Parcourez chaque rotation pour calculer la durée entre zone_in et zone_out
        foreach ($rotations as $rotation) {
            if ($rotation->type === 'zone_in') {
                // Récupérez la date de zone_in
                $zoneInTime = Carbon::parse($rotation->date_heure);
            } elseif ($rotation->type === 'zone_out') {
                // Récupérez la date de zone_out
                $zoneOutTime = Carbon::parse($rotation->date_heure);

                // Calculez la durée entre zone_in et zone_out
                $duration = $zoneOutTime->diffInHours($zoneInTime);
                $totalDuration += $duration;
                

                // Ajoutez la durée à un tableau pour chaque rotation
                $rotationDurationsDetail[] = [
                    'zone_in' => $zoneInTime->format('Y-m-d H:i:s'),
                    'zone_out' => $zoneOutTime->format('Y-m-d H:i:s'),
                    'duration_hours' => $duration,
                ];

                // Réinitialisez les temps pour la prochaine rotation
                $zoneInTime = null;
                $zoneOutTime = null;
            }
        }
        

        // Retournez le tableau contenant les durées pour chaque rotation
        return $data = [
            'détail' => $rotationDurationsDetail,
            'totalHours' => $totalDuration
        ];
    }

    public function getTotalRotationDuration($vehicle){
        // Sélectionnez toutes les rotations pour le véhicule donné
        $rotations = Rotation::where('vehicule', '=' ,$vehicle)->get();
        // Initialisez la durée totale à zéro
        $totalDuration = Carbon::now()->diffInSeconds(Carbon::now()); // Initialisez à 0 secondes

        // Parcourez chaque rotation pour calculer la durée totale
        foreach ($rotations as $rotation) {
            // Vérifiez si c'est une rotation de zone_in
            if ($rotation->type === 'zone_in') {
                // Récupérez la date et l'heure de zone_in
                $zoneInDateTime = Carbon::parse($rotation->date_heure);
                
                // Trouvez la rotation de zone_out correspondante
                $zoneOutRotation = $rotations->where('type', 'zone_out')
                    ->where('date_heure', '>', $zoneInDateTime)
                    ->first();

                // Si une rotation de zone_out est trouvée, calculez la durée de rotation
                if ($zoneOutRotation) {
                    $zoneOutDateTime = Carbon::parse($zoneOutRotation->date_heure);
                    $rotationDuration = $zoneOutDateTime->diffInSeconds($zoneInDateTime);

                    // Ajoutez la durée de rotation à la durée totale
                    $totalDuration += $rotationDuration;
                }
            }
        }
        // Convertissez la durée totale en heures, minutes et secondes
        $hours = floor($totalDuration / 3600);
        $totalDuration %= 3600;
        $minutes = floor($totalDuration / 60);
        $seconds = $totalDuration % 60;
        

        // Retournez la durée totale sous forme de tableau ou de chaîne formatée
        return ['hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds];
    }

    public function getTotalDuoRotationDuration($vehicle){
        // Sélectionnez toutes les rotations pour le véhicule donné
        $rotations = Rotation::where('vehicule', $vehicle)->get();

        // Initialisez la durée totale à zéro
        $totalDurationSeconds = 0;

        // Parcourez chaque rotation pour calculer la durée totale
        $lastZoneInTime = null;
        foreach ($rotations as $rotation) {
            // Si c'est une rotation de zone_in, enregistrez l'heure
            if ($rotation->type === 'zone_in') {
                $lastZoneInTime = Carbon::parse($rotation->date_heure);
            } elseif ($rotation->type === 'zone_out' && $lastZoneInTime) {
                // Si c'est une rotation de zone_out et qu'on a déjà un enregistrement de zone_in
                $zoneOutTime = Carbon::parse($rotation->date_heure);
                // Calculez la durée de la rotation et ajoutez-la à la durée totale
                $rotationDuration = $lastZoneInTime->diffInSeconds($zoneOutTime);
                $totalDurationSeconds += $rotationDuration;
                // Réinitialisez lastZoneInTime pour la prochaine paire de rotations
                $lastZoneInTime = null;
            }
        }

        // Convertissez la durée totale en heures, minutes et secondes
        $totalDuration = Carbon::createFromTimestamp($totalDurationSeconds)->format('H:i:s');

        dd($totalDuration);
        // Retournez la durée totale
        return $totalDuration;
    }

    /**
     * Show the form for creating a new Rotation.
     *
     * @return Response
     */
    public function create()
    {
        return view('rotations.create');
    }

    /**
     * Store a newly created Rotation in storage.
     *
     * @param CreateRotationRequest $request
     *
     * @return Response
     */
    public function store(CreateRotationRequest $request)
    {
        $input = $request->all();

        $rotation = $this->rotationRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/rotations.singular')]));

        return redirect(route('rotations.index'));
    }

    /**
     * Display the specified Rotation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $rotation = $this->rotationRepository->find($id);

        if (empty($rotation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/rotations.singular')]));

            return redirect(route('rotations.index'));
        }

        return view('rotations.show')->with('rotation', $rotation);
    }


    /**
     * Show the form for editing the specified Rotation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $rotation = $this->rotationRepository->find($id);

        if (empty($rotation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/rotations.singular')]));

            return redirect(route('rotations.index'));
        }

        return view('rotations.edit')->with('rotation', $rotation);
    }

    /**
     * Update the specified Rotation in storage.
     *
     * @param int $id
     * @param UpdateRotationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRotationRequest $request)
    {
        $rotation = $this->rotationRepository->find($id);
        $data = $request->all();

        if (isset($data['date_heur'])) {
            $data['date_heur'] = Carbon::parse($data['date_heur'])->format('Y-m-d H:i:s');
        }

        if (empty($rotation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/rotations.singular')]));

            return redirect(route('rotations.index'));
        }
       

        $rotation = $this->rotationRepository->update($data, $id);

        Flash::success(__('messages.updated', ['model' => __('models/rotations.singular')]));

        return redirect(route('rotations.index'));
    }

    /**
     * Remove the specified Rotation from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $rotation = $this->rotationRepository->find($id);

        if (empty($rotation)) {
            Flash::error(__('messages.not_found', ['model' => __('models/rotations.singular')]));

            return redirect(route('rotations.index'));
        }

        $this->rotationRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/rotations.singular')]));

        return redirect(route('rotations.index'));
    }
}
