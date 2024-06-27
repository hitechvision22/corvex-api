<?php

namespace App\Jobs\users;

use App\Mail\users\ConfirmToClientMeetForTrajetMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ConfirmToClientMeetForTrajetjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $client,$chauffeur;
    public function __construct($client,$chauffeur)
    {
        $this->client = $client;
        $this->chauffeur = $chauffeur;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->client->email)
        ->send(new ConfirmToClientMeetForTrajetMail($this->client,$this->chauffeur));
    }
}
