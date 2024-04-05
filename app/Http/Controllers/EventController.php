<?php

namespace App\Http\Controllers;

use App\DataTables\EventDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Repositories\EventRepository;
use App\Http\Controllers\AppBaseController;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Session;
use App\Models\Event;
use App\Models\Penalite;
use App\Models\Chauffeur;
use GuzzleHttp\Client;
use Response;

class EventController extends AppBaseController
{
    /** @var EventRepository $eventRepository*/
    private $eventRepository;

    public function __construct(EventRepository $eventRepo)
    {
        $this->eventRepository = $eventRepo;
    }   

    public function viewScoring(){
        $drivers = Chauffeur::all()->pluck('nom')->toArray();
        
        return view('events.scoring', compact('drivers'));
    }

    /**
     * Display a listing of the Event.
     *
     * @param EventDataTable $eventDataTable
     *
     * @return Response
     */
    public function index(EventDataTable $eventDataTable)
    {
        // getEventFromApi();
        // createExistingDriverInEvent();
        return $eventDataTable->render('events.index');
    }


    public function getRoutes(){
        dd(getRoutes());
    }

    /**
     * Show the form for creating a new Event.
     *
     * @return Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created Event in storage.
     *
     * @param CreateEventRequest $request
     *
     * @return Response
     */
    public function store(CreateEventRequest $request)
    {
        $input = $request->all();

        $event = $this->eventRepository->create($input);

        Alert::success(__('messages.saved', ['model' => __('models/events.singular')]));

        return redirect(route('events.index'));
    }

    /**
     * Display the specified Event.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Alert::error(__('messages.not_found', ['model' => __('models/events.singular')]));

            return redirect(route('events.index'));
        }

        return view('events.show')->with('event', $event);
    }

    /**
     * Show the form for editing the specified Event.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Alert::error(__('messages.not_found', ['model' => __('models/events.singular')]));

            return redirect(route('events.index'));
        }

        return view('events.edit')->with('event', $event);
    }

    /**
     * Update the specified Event in storage.
     *
     * @param int $id
     * @param UpdateEventRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEventRequest $request)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Alert::error(__('messages.not_found', ['model' => __('models/events.singular')]));

            return redirect(route('events.index'));
        }

        $event = $this->eventRepository->update($request->all(), $id);

        Alert::success(__('messages.updated', ['model' => __('models/events.singular')]));

        return redirect(route('events.index'));
    }

    /**
     * Remove the specified Event from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Alert::error(__('messages.not_found', ['model' => __('models/events.singular')]));

            return redirect(route('events.index'));
        }

        $this->eventRepository->delete($id);

        Alert::success(__('messages.deleted', ['model' => __('models/events.singular')]));

        return redirect(route('events.index'));
    }
}
