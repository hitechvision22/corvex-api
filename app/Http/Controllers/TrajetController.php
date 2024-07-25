<?php

namespace App\Http\Controllers;

use App\Jobs\Trajet\SendInfoTrajetjob;
use App\Jobs\Trajet\UpdateEtatTrajetjob;
use App\Mail\Trajet\SendInfoTrajetMail;
use App\Mail\Trajet\UpdateEtatTrajetmail;
use App\Models\Frais;
use App\Models\Piece;
use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TrajetController extends Controller
{
    public function index()
    {
        $trajet = Trajet::orderBy('created_at', 'DESC')->simplePaginate(30);
        return response()->json($trajet);
    }

    public function MyPosts()
    {
        $trajet = Trajet::where('user_id',Auth::user()->id)->orderBy('created_at', 'DESC')->simplePaginate(30);
        return response()->json($trajet);
    }

    public function verifiedCar()
    {
        $user = User::find(Auth()->user()->id);
        if (!$user->vehicule) {
            return response()->json(['message' => 'no car'], 422);
        }
        return response()->json(true);
    }

    public function store(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $trajet = new Trajet();
        $trajet->ville_depart = $request->ville_depart;
        $trajet->point_rencontre = $request->point_rencontre;
        $trajet->ville_destination = $request->ville_destination;
        $trajet->point_destination = $request->point_destination;
        $trajet->date_depart = $request->date_depart;
        $trajet->heure_depart = $request->heure_depart;
        $trajet->prix = $request->prix ? $request->prix : 0;
        $trajet->nombre_de_place = (int)$request->nombre_place;
        $trajet->nombre_de_place_disponible = (int)$request->nombre_place;
        $trajet->user_id = Auth::user()->id;
        $trajet->Mode_de_paiement = $request->Mode_de_paiement;
        $trajet->bagage = $request->bagage;
        $trajet->etat = 'non-actif';
        $trajet->save();

        Mail::to(Auth::user()->email)
            ->send(new SendInfoTrajetMail($trajet, Auth::user()));
        return response()->json($trajet);
    }

    public function show($id)
    {
        $trajet = Trajet::findOrFail($id);
        return response()->json($trajet);
    }

    public function update(Request $request, $id)
    {
        Trajet::findOrFail($id)->update($request->all());
        return response()->json(['message' => 'trajet mis a jour']);
    }
    public function UpdateEtatTrajet($id)
    {

        $trajet = Trajet::with('user')->find($id);

        Trajet::find($id)->update([
            'etat' => request('etat'),
            'nombre_de_place_disponible' => $trajet->nombre_de_place,
        ]);

        $frais = Frais::where('raison','revenus')->first();
        $reservations = Reservation::with('trajet','user')->where('trajet_id',$trajet->id)->get();

        foreach ($reservations as $reservation) {
           $client = $reservation->user;
           $trajet = $reservation->trajet;

           $MontantRetour = ($trajet * $reservation->nbr_place) + $frais->montant;

           $clientWallet = Wallet::where("user_id",$client->id)->first();
           $clientWallet->montant += $MontantRetour;
           $clientWallet->update();

           $adminWallet = Wallet::where("user_id",1)->first();
           $adminWallet->montant -= $MontantRetour;
           $adminWallet->update();
        }

        Mail::to($trajet->user->email)
            ->send(new UpdateEtatTrajetmail(User::find($trajet->user_id),$trajet));
        return response()->json(['message' => 'etat du trajet mis a jour']);
    }

    public function destroy($id)
    {
        Trajet::findOrFail($id)->delete();
        return response()->json(['message' => 'trajet supprime']);
    }

    public function DeletedLastPost(){
        $trajets = Trajet::where('date_depart','<',Carbon::now())->get();

        foreach ($trajets as $trajet) {
            $reservations = Reservation::where('trajet_id',$trajet->id)->get();
            foreach ($reservations as $reservation) {
                $walletUser = Wallet::where("user_id", $reservation->user_id)->first();
                $walletUser->montant += $reservation->nbr_place * $trajet->prix;
                $walletUser->update();
            }

            $walletUser = Wallet::where("user_id", $reservation->user_id)->first();
            $walletUser->montant += $reservation->nbr_place * $trajet->prix;
            $walletUser->update();
            $trajet->delete();
        }
        return response()->json(['message'=>'ancien post supprime']);
    }
    
}
