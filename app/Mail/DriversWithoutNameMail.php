<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Chauffeur;

class DriversWithoutNameMail extends Mailable
{
    use Queueable, SerializesModels;

    public $drivers;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($drivers)
    {
        $this->drivers = $drivers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Chauffeurs non fixe')
                    ->view('emails.drivers_without_name');
    }
}
