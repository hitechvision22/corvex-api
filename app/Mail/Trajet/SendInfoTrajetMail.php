<?php

namespace App\Mail\Trajet;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInfoTrajetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $trajet,$user;
    public function __construct($trajet,$user)
    {
        $this->trajet = $trajet;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nouveau covoiturage sur Corvex')
            ->view('mails.trajets.NewPostInfo', [
                'trajet' => $this->trajet,
                'user' => $this->user,
            ]);
    }
}
