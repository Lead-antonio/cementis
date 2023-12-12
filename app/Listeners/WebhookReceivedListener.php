<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\WebhookReceived;

class WebhookReceivedListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        
        $webhookData = $event->webhookData;   
        info('WebhookReceivedListener handle method reached');
        info($webhookData);
    }
}
