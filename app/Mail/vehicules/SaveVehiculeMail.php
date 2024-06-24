<?php

namespace App\Mail\vehicules;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaveVehiculeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user,$vehicule;
    public function __construct( $user,$vehicule)
    {
        $this->user = $user;
        $this->vehicule = $vehicule;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.vehicules.SaveCar',[
            'user' => $this->user,
            'vehicule' => $this->vehicule,
        ]);
    }
}
