<?php

namespace App\Http\Controllers;

use App\DataTables\MovementDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMovementRequest;
use App\Http\Requests\UpdateMovementRequest;
use App\Repositories\MovementRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class NotificationController extends AppBaseController
{

    /**
     * Summary of markAsRead
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsRead()
    {
        try {
            auth()->user()->unreadNotifications->markAsRead();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }


}