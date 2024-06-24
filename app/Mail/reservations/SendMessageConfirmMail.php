<?php

namespace App\Mail\reservations;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMessageConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user,$trajet,$reservation,$chauffeur;
    public function __construct($user,$trajet,$reservation,$chauffeur)
    {
        $this->user = $user;
        $this->trajet = $trajet;
        $this->reservation = $reservation;
        $this->chauffeur = $chauffeur;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('nouvelle place reserve sur Corvex')
        ->view('mails.reservations.SendMessageConfirm', [
            'trajet' => $this->trajet,
            'reservation' => $this->reservation,
            'chauffeur' => $this->chauffeur,
            'client' => $this->user,
        ]);
    }
}
