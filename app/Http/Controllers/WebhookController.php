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
        event(new WebhookReceived($webhookData));

        // Condition pour vérifier si $webhookData est vide
        if (empty($webhookData)) {
            // Si $webhookData est vide, vous pouvez retourner une indication pour rafraîchir la page
            return response()->json(['Webhook' => $webhookData, 'refresh' => true]);
        }

        // Répondez au service émetteur du webhook si nécessaire
        return response()->json(['Webhook' => $webhookData, 'refresh' => false]);
    }
}
