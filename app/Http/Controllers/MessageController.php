<?php

namespace App\Http\Controllers;

use App\DataTables\MessageDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Repositories\MessageRepository;
use Twilio\Rest\Client;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class MessageController extends AppBaseController
{
    /** @var MessageRepository $messageRepository*/
    private $messageRepository;

    public function __construct(MessageRepository $messageRepo)
    {
        $this->messageRepository = $messageRepo;
    }

    /**
     * Display a listing of the Message.
     *
     * @param MessageDataTable $messageDataTable
     *
     * @return Response
     */
    public function index(MessageDataTable $messageDataTable)
    {
        return $messageDataTable->render('messages.index');
    }

    /**
     * Show the form for creating a new Message.
     *
     * @return Response
     */
    public function create()
    {
        return view('messages.create');
    }

    public function sendSms($apiType,$input){
        if($apiType === "VONAGE"){
            $VONAGE_KEY = env('VONAGE_API_KEY');
            $VONAGE_SECRET = env('VONAGE_API_SECRET');
            $basic = new \Vonage\Client\Credentials\Basic($VONAGE_KEY, $VONAGE_SECRET);
            $client = new \Vonage\Client($basic);
            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS($input['destinataire'], env('BRAND_NAME'), $input['contenu'])
            );

            $message = $response->current();
            return [
                'status' => $message->getStatus(),
                'error' => $message->getStatus() !== 0, // Check if status is not 0 for an error
                'message' => $message->getStatus() == 0 ? 'The message was sent successfully.' : 'The message failed with status: ' . $message->getStatus(),
            ];

        }
        if($apiType === "TWILIO"){
            $client = new Client(env('TWILIO_SDI'), env('TWILIO_TOKEN'));
            $message = $client->messages->create(
                '+'.$input['destinataire'],
                array(
                    'from' => env('TWILIO_PHONE'),
                    'body' => $input['contenu']
                )
            );
            return [
                'status' => $message->status,
                'error' => $message->status !== 'queued', // Check if status is not 'sent' for an error
                'message' => $message->status == 'queued' ? 'The message was sent successfully.' : 'The message failed with status: ' . $message->status,
            ];
        }
    }

    /**
     * Store a newly created Message in storage.
     *
     * @param CreateMessageRequest $request
     *
     * @return Response
     */
    public function store(CreateMessageRequest $request)
    {
        $input = $request->all();
        $apiType = $input['api'];
        $result = $this->sendSms($apiType, $input);
        if (!$result['error']) {
            $mess = $this->messageRepository->create($input);
            Flash::success($result['message']);
            return redirect(route('messages.index'));
        } else {
            Flash::error($result['message']);
            return redirect(route('messages.index'));
        }
    }

    /**
     * Display the specified Message.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $message = $this->messageRepository->find($id);

        if (empty($message)) {
            Flash::error(__('messages.not_found', ['model' => __('models/messages.singular')]));

            return redirect(route('messages.index'));
        }

        return view('messages.show')->with('message', $message);
    }

    /**
     * Show the form for editing the specified Message.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $message = $this->messageRepository->find($id);

        if (empty($message)) {
            Flash::error(__('messages.not_found', ['model' => __('models/messages.singular')]));

            return redirect(route('messages.index'));
        }

        return view('messages.edit')->with('message', $message);
    }

    /**
     * Update the specified Message in storage.
     *
     * @param int $id
     * @param UpdateMessageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMessageRequest $request)
    {
        $message = $this->messageRepository->find($id);

        if (empty($message)) {
            Flash::error(__('messages.not_found', ['model' => __('models/messages.singular')]));

            return redirect(route('messages.index'));
        }

        $message = $this->messageRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/messages.singular')]));

        return redirect(route('messages.index'));
    }

    /**
     * Remove the specified Message from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $message = $this->messageRepository->find($id);

        if (empty($message)) {
            Flash::error(__('messages.not_found', ['model' => __('models/messages.singular')]));

            return redirect(route('messages.index'));
        }

        $this->messageRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/messages.singular')]));

        return redirect(route('messages.index'));
    }
}
