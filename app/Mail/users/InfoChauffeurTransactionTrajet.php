<?php

namespace App\Mail\users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoChauffeurTransactionTrajet extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $chauffeur, $transaction,$trajet;
    public function __construct($chauffeur, $transaction,$trajet)
    {
        $this->chauffeur = $chauffeur;
        $this->transaction = $transaction;
        $this->trajet = $trajet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirmation de Fin de Voyage')
        ->view('mails.reservations.ConfirmVoyage', [
            'chauffeur' => $this->chauffeur,
            'transaction' => $this->transaction,
            'trajet' => $this->trajet,
        ]);
    }
}
