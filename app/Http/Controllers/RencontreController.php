<?php

namespace App\Http\Controllers;

use App\Jobs\users\AvertToClientMeetForTrajetjob;
use App\Jobs\users\ConfirmToClientMeetForTrajetjob;
use App\Models\Rencontre;
use App\Models\Trajet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RencontreController extends Controller
{
    // le chauffeur accepte que le client est arrive au point de rencontre
    public function Meeting($ClientId,$id){
       $rencontre = Rencontre::create([
            'id_chauffeur'=>Auth::user()->id,
            'id_client'=>$ClientId,
            'checkChauffeur'=>true,
            'status'=>'meet',
            'id_trajet'=>$id,
        ]);

        AvertToClientMeetForTrajetjob::dispatch(User::find($ClientId),Auth::user())->onQueue('userEmail');
        return response()->json($rencontre);

        // info client
    }

    // valider l'arrive par le client et chargement de l'etat du trajet
    public function ValidedTrajet(Request $request,$RenId,$id){

        Trajet::find($id)->update(['status'=>$request->status,'etat'=>$request->etat]);

        $rencontre = Rencontre::find($RenId);
        $rencontre->update(['checkClient'=> true]);

        ConfirmToClientMeetForTrajetjob::dispatch(User::find($rencontre->id_client),User::find($rencontre->id_chauffeur))->onQueue('userEmail');

        return response()->json(['message'=>'client satisfait']);
    }
}
