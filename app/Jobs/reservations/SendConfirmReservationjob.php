<?php

namespace App\Jobs\reservations;

use App\Mail\reservations\SendConfirmReservationMail;
use App\Mail\reservations\SendConfirmReservationToClientMail;
use App\Notifications\reservations\SendConfirmReservationNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendConfirmReservationjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        Mail::to($this->chauffeur->email)
        ->send(new SendConfirmReservationToClientMail($this->trajet, $this->user,$this->reservation,$this->chauffeur));
        Mail::to($this->chauffeur->email)
        ->send(new SendConfirmReservationMail($this->trajet, $this->user,$this->reservation,$this->chauffeur));
        // envoyer une notification au chauffeur
        $this->user->notify(new SendConfirmReservationNotification($this->user,$this->trajet,$this->reservation,$this->chauffeur));
    }
}
