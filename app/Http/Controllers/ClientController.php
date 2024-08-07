<?php

namespace App\Http\Controllers;

use App\Mail\users\InfoChauffeurTransactionTrajet;
use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\Foreach_;

class ClientController extends Controller
{
    /**
     * 
     *  on veut trouver un trajet (depart,date_depart,heure_depart,arrive)
     *      on affiche d'abord tout les 30 nouveaux trajets
     *      si on a le depart => on fait un filtre par rapport de cela
     *      
     * 
     * */
    public function search(Request $request)
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $trajets = Trajet::when($request->input('ville_depart'), function ($query, $villeDepart) {
            $query->where('ville_depart', 'like', "%{$villeDepart}%");
        })->when($request->input('ville_destination'), function ($query, $villeDestination) {
            $query->where('ville_destination', 'like', "%{$villeDestination}%");
        })->when($request->input('date_depart'), function ($query, $dateDepart) {
            $query->where('date_depart', '>=', $dateDepart);
        })->where('etat', 'Actif')->where('date_depart', '>=', $currentDate)->SimplePaginate(30);

        return response()->json($trajets);
    }

    public function notification()
    {
        $user = Auth::user();
        $allNotifications = $user->notifications;
        $user->unreadNotifications->markAsRead();

        return response()->json([$allNotifications]);
    }

    public function CleanData()
    {
        $currentDate = Carbon::now()->format('Y-m-d');


        $trajets = Trajet::all();
        foreach ($trajets as $trajet) {
            return response()->json($trajet->date_depart < $currentDate);
            if ($trajet->date_depart < $currentDate) {
                $reservations = Reservation::where("trajet_id", $trajet->id)->get();
                
                foreach ($reservations as $reservation) {
                    $montant = $reservation->nbr_place + $trajet->prix;
                    $chauffeurWallet = Wallet::where("user_id", $trajet->user_id)->first();
                    $chauffeurWallet += $montant;
                    $chauffeurWallet->update();

                    $transaction = new Transaction();
                    $transaction->libelle = 'retour de voyage';
                    $transaction->date = Carbon::now();
                    $transaction->montant = $montant;
                    $transaction->balance = $chauffeurWallet->montant + $montant;
                    $transaction->wallet_id = $chauffeurWallet->id;
                    $transaction->reservation_id = $reservation->id;
                    $transaction->status = 'credit';
                    $transaction->save();

                    $admin = User::where("type", 4)->first();
                    $walletAdmin = Wallet::where("user_id", $admin->id)->first();
                    $walletAdmin->montant -= $montant;
                    $walletAdmin->update();

                    $trans = new Transaction();
                    $trans->libelle = 'remise de fond';
                    $trans->date = Carbon::now();
                    $trans->montant = $montant;
                    $trans->balance = $walletAdmin->montant - $montant;
                    $trans->wallet_id = $walletAdmin->id;
                    $trans->reservation_id = $reservation->id;
                    $trans->status = 'debit';
                    $trans->save();


                    // envoyer un mail a l'admin
                    Mail::to($admin->email)
                        ->send(new InfoChauffeurTransactionTrajet($admin,$transaction,$trajet));
                    // envoyer le mail au chauffeur
                    $chauffeur= User::find($trajet->user_id);
                    Mail::to($chauffeur->email)
                        ->send(new InfoChauffeurTransactionTrajet($chauffeur,$transaction,$trajet));
                    // envoyer le mail au client
                    $client= User::find($reservation->user_id);
                    Mail::to($chauffeur->email)
                        ->send(new InfoChauffeurTransactionTrajet($client,$transaction,$trajet));
                }
                $trajet->delete();
            }
        }

        return response()->json(['message'=>"mis a jour des trajets"]);
    }
}
