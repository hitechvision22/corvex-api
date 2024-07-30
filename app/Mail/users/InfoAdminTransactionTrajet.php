<?php

namespace App\Mail\users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoAdminTransactionTrajet extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $admin, $transaction,$trajet;
    public function __construct($admin, $transaction,$trajet)
    {
        $this->admin = $admin;
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
        return $this->subject('Débit de Compte')
        ->view('mails.reservations.RemiseFond', [
            'admin' => $this->admin,
            'transaction' => $this->transaction,
            'trajet' => $this->trajet,
        ]);
    }
}
