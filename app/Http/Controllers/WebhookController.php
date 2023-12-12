<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\WebhookReceived;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Accédez aux données du webhook
        $webhookData = $request->all();
        info('WebhookController handle method reached');
        event(new WebhookReceived($webhookData));
    }
}
