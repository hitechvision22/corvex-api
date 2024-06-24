<?php

namespace App\Mail\Trajet;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateEtatTrajetmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
  public $user,$trajet;
    public function __construct($user,$trajet)
    {
        $this->user = $user;
        $this->trajet = $trajet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('mis a jour de votre post sur Corvex')
            ->view('mails.trajets.UpdateEtatPost', [
                'trajet' => $this->trajet,
                'user' => $this->user,
            ]);
    }
}
