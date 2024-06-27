<?php

namespace App\Mail\users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmToChauffeurMeetForTrajetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $client, $chauffeur;
    public function __construct($client, $chauffeur)
    {
        $this->client = $client;
        $this->chauffeur = $chauffeur;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('trajet terminé')
            ->view('mails.trajets.confirmationChauffeur', [
                'client' => $this->client,
                'chauffeur' => $this->chauffeur,
            ]);
    }
}
