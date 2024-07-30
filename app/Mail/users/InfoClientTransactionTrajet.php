<?php

namespace App\Mail\users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoClientTransactionTrajet extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $client, $transaction,$trajet;
    public function __construct($client, $transaction,$trajet)
    {
        $this->client = $client;
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
        ->view('mails.reservations.RemiseFond', [
            'client' => $this->client,
            'transaction' => $this->transaction,
            'trajet' => $this->trajet,
        ]);
    }
}
