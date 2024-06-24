<?php

namespace App\Jobs\Trajet;

use App\Mail\Trajet\UpdateEtatTrajetmail;
use App\Models\Trajet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class UpdateEtatTrajetjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $trajetId;
    public function __construct($trajetId)
    {
        $this->trajetId = $trajetId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $trajet = Trajet::with('user')->find($this->trajetId);
        Mail::to($trajet->user->email)
        ->send(new UpdateEtatTrajetmail($trajet, $trajet->user->email));
    }
}
