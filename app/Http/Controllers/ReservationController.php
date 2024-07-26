<?php

namespace App\Http\Controllers;

use App\Jobs\reservations\SendConfirmReservationjob;
use App\Mail\reservations\SendConfirmReservationMail;
use App\Mail\reservations\SendConfirmReservationToClientMail;
use App\Models\CodePromo;
use App\Models\Conversation;
use App\Models\Frais;
use App\Models\Message;
use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\reservations\SendConfirmReservationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('user', 'trajet')->orderBy('created_at', 'DESC')->simplePaginate(30);
        return  response()->json($reservations);
    }

    public function deletedReservation($id)
    {
        Reservation::find($id)->delete();
        return  response()->json(['message' => 'reservation supprime']);
    }

    public function updatedResev(Request $request, $id)
    {
        Reservation::find($id)->update($request->all());
        return  response()->json(['message' => 'reservation mis a jour']);
    }

    public function store(Request $request)
    {
        $exist = Reservation::where("user_id", Auth::user()->id)->where('status', 'accepté')->first();
        if ($exist) {
            return response()->json(['message' => 'vous avez deja une reservation accepté']);
        }
        $reservation = new Reservation();
        $reservation->trajet_id = $request->trajet_id;
        $reservation->user_id = Auth::user()->id; //client
        $reservation->nbr_place = (int)$request->nbr_place;
        $reservation->save();

        $trajet = Trajet::find($request->trajet_id);
        $trajet->nombre_de_place_disponible -= $request->nbr_place;
        $trajet->update();

        $wallet = Wallet::where('user_id', $trajet->user_id)->first();
        $frais = Frais::where('raison', 'revenus')->first();

        $revenus = $reservation->nbr_place * $trajet->prix;
        $admin = User::where("type", 4)->first();
        $walletAdmin = Wallet::where("user_id", $admin->id)->first();
        if (!$walletAdmin) {
            Wallet::create(['user_id' => $admin->id, 'montant' => $revenus]);
        }
        $walletAdmin->montant += $revenus;

        $transaction = new Transaction();
        $transaction->libelle = 'reservation';
        $transaction->date = Carbon::now();
        $transaction->montant = $request->montant;
        $transaction->balance = $wallet->montant + $request->montant;
        $transaction->wallet_id = $wallet->id;
        $transaction->reservation_id = $reservation->id;
        $transaction->status = 'credit';
        $transaction->save();

        $wallet->montant = $wallet->montant + $request->montant;
        $wallet->update();


        $conversation = Conversation::create([
            'user1_id' => $trajet->user_id,
            'trajet_id' => $trajet->id,
            'user2_id' => Auth::user()->id,
            'sender_id' => Auth::user()->id,
            'last_message' => "bonjour, j'ai pris une reservation",
            'reservation_id' => $reservation->id,
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::user()->id,
            'recipient_id' => $trajet->user_id,
            'message_text' => "bonjour, j'ai pris une reservation",
        ]);


        Mail::to(User::find($trajet->user_id)->email)
            ->send(new SendConfirmReservationToClientMail(Auth::user(), $trajet, $reservation, User::find($trajet->user_id)));
        Mail::to(User::find($trajet->user_id)->email)
            ->send(new SendConfirmReservationMail(Auth::user(), $trajet, $reservation, User::find($trajet->user_id)));
        // envoyer une notification au chauffeur
        $user = User::find(Auth::user()->id);
        $user->notify(new SendConfirmReservationNotification(Auth::user(), $trajet, $reservation, User::find($trajet->user_id)));

        return  response()->json(['message' => 'reservation enregistrer']);
    }

    public function Reservations()
    {
        $reservations = Reservation::with('trajet')->where('user_id', Auth::user()->id)->simplePaginate(30);
        return response()->json($reservations);
    }

    public function FindReservation($id)
    {
        $reservation = Reservation::with('trajet', 'user')->find($id);
        $trajet = $reservation->trajet;
        $reservation['chauffeur'] = User::find($trajet->user_id);
        return response()->json($reservation);
    }

    public function getFrais()
    {
        $frais = Frais::where('raison', 'revenus')->first();
        return response()->json($frais);
    }

    public function updateFrais(Request $request)
    {
        Frais::where('raison', $request->raison)->update(['montant' => $request->montant]);
        return response()->json(['frais mis a jour']);
    }

    // annuler une reservation
    public function DeniedReservation($ReservationId)
    {
        $reservation = Reservation::find($ReservationId);
        $reservation->status = 'annuler';
        $reservation->update();

        $trajet = Trajet::find($reservation->trajet_id);
        $trajet->nombre_de_place_disponible += $reservation->nbr_place;
        $trajet->update();

        $user = User::find(Auth::user()->id);
        if ($user->id == $reservation->user_id) {
            // covex recupere ses frais et donne les 30% de la reservation et le reste est renvoye a l'utilisateur
            $walletChauffeur = Wallet::where("user_id", $trajet->user_id)->first();
            $walletChauffeur->montant += ($reservation->nbr_place * $trajet->prix) * 0.3;
            $walletChauffeur->update();

            $transaction = new Transaction();
            $transaction->libelle = 'remboursement';
            $transaction->date = Carbon::now();
            $transaction->montant = ($reservation->nbr_place * $trajet->prix) * 0.3;
            $transaction->balance = $walletChauffeur->montant + ($reservation->nbr_place * $trajet->prix) * 0.3;
            $transaction->wallet_id = $walletChauffeur->id;
            $transaction->reservation_id = $reservation->id;
            $transaction->status = 'credit';
            $transaction->save();


            $walletUser = Wallet::where("user_id", $reservation->user_id)->first();
            $walletUser->montant += ($reservation->nbr_place * $trajet->prix) * 0.7;
            $walletUser->update();

            $transaction = new Transaction();
            $transaction->libelle = 'remboursement';
            $transaction->date = Carbon::now();
            $transaction->montant = ($reservation->nbr_place * $trajet->prix) * 0.7;
            $transaction->balance = $walletUser->montant + ($reservation->nbr_place * $trajet->prix) * 0.7;
            $transaction->wallet_id = $walletUser->id;
            $transaction->reservation_id = $reservation->id;
            $transaction->status = 'credit';
            $transaction->save();

            $admin = User::where("type", 4)->first();
            $walletAdmin = Wallet::where("user_id", $admin->id)->first();
            $walletAdmin->montant -= $reservation->nbr_place * $trajet->prix;
            $walletAdmin->update();


            $transaction = new Transaction();
            $transaction->libelle = 'remboursement';
            $transaction->date = Carbon::now();
            $transaction->montant = ($reservation->nbr_place * $trajet->prix) * 0.3;
            $transaction->balance = $walletAdmin->montant + $reservation->nbr_place * $trajet->prix;
            $transaction->wallet_id = $walletAdmin->id;
            $transaction->reservation_id = $reservation->id;
            $transaction->status = 'debit';
            $transaction->save();
        } else {
            $reservations = Reservation::where('trajet_id', $trajet->id)->get();

            foreach ($reservations as $reservation) {
                $walletUser = Wallet::where("user_id", $reservation->user_id)->first();
                $walletUser->montant += $reservation->nbr_place * $trajet->prix;
                $walletUser->update();
            }

            $walletUser = Wallet::where("user_id", $reservation->user_id)->first();
            $walletUser->montant += $reservation->nbr_place * $trajet->prix;
            $walletUser->update();
        }

        return response()->json(['message' => 'reservation annuler']);
    }

    // afficher tout les clients d'un trajet
    public function ClientTrajet($id)
    {
        $trajet = Trajet::find($id);
        $reservations = Reservation::with('user')->where('trajet_id', $trajet->id)->get();
        return response()->json($reservations);
    }

    
}
