<?php

namespace App\Http\Controllers;

use App\Jobs\reservations\SendConfirmReservationjob;
use App\Mail\reservations\SendConfirmReservationMail;
use App\Mail\reservations\SendConfirmReservationToClientMail;
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
            'last_message' => " bonjour, j'ai pris une reservation ",
            'reservation_id' => $reservation->id,
        ]);

        Message::create([
            'conversation_id'=> $conversation->id,
            'sender_id' => Auth::user()->id,
            'recipient_id' => $trajet->user_id,
            'message_text' => " bonjour, j'ai pris une reservation ",
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
}
