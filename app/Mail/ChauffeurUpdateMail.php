<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChauffeurUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $chauffeur_updates;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($chauffeur_updates)
    {
        $this->chauffeur_updates = $chauffeur_updates;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Mise à jour information chauffeur')
        ->view('emails.chauffeur_update');
    }
}
