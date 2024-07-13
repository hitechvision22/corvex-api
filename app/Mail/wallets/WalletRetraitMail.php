<?php

namespace App\Mail\wallets;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WalletRetraitMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user,$transaction;
    public function __construct($user,$transaction)
    {
        $this->user = $user;
        $this->transaction = $transaction;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.wallets.retrait',[
            'user' => $this->user,
            'transaction' => $this->transaction,
        ]);
    }
}
