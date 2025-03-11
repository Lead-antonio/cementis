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
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($chauffeur)
    {
        $this->chauffeur = $chauffeur;
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
            'message' => "Demande de mise à jour sur l'information du chauffeur ". $this->chauffeur,
            'url' => route('chauffeurUpdateStorie.validation_list'),
        ];
    }

}
