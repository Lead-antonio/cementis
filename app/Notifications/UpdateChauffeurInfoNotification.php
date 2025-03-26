<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateChauffeurInfoNotification extends Notification
{
    use Queueable;

    protected $chauffeur;
    protected $user_request;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($chauffeur,$user_request)
    {
        $this->chauffeur = $chauffeur;
        $this->user_request = $user_request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->user_request .  " demande la mise à jour du chauffeur ". $this->chauffeur,
            'url' => route('chauffeurUpdateStorie.validation_list'),
        ];
    }

}
