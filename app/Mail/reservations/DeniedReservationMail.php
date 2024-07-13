<?php

namespace App\Mail\reservations;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeniedReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $client,$trajet;
    public function __construct($client,$trajet)
    {
        $this->client = $client;
        $this->trajet = $trajet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Annulation de reservation')
        ->view('mails.reservations.DeniedClientReservation', [
            'client' => $this->client,
            'trajet' => $this->trajet,
        ]);
    }
}
