<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateChauffeurInfoNotification extends Notification
{
   
   
    protected $chauffeur;
    protected $user_request;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_request,$chauffeur)
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
            'message' => $this->user_request . " demande la création d'un nouveau chauffeur ". $this->chauffeur,
            'url' => route('chauffeurUpdateStorie.validation_list'),
        ];
    }

}
