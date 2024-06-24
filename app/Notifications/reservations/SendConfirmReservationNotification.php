<?php

namespace App\Notifications\reservations;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendConfirmReservationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $user, $trajet, $reservation, $chauffeur;
    public function __construct($user, $trajet, $reservation, $chauffeur)
    {
        $this->user = $user;
        $this->trajet = $trajet;
        $this->reservation = $reservation;
        $this->chauffeur = $chauffeur;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // exclusivement pour le client
        return [
            'user' => json_encode($this->user),
            'titre'=>'nouvelle reservation',
            'description' => "Votre reservation de " . $this->reservation->nbr_place . " places a ete accepte",
            'type' => 'reservation',
            'id' => $this->reservation->id
        ];
    }
}
