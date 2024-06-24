<?php

namespace App\Http\Controllers;

use App\Jobs\reservations\SendConfirmReservationjob;
use App\Models\Frais;
use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $reservation->nbr_place = $request->nbr_place;
        $reservation->save();

        $trajet = Trajet::find($request->trajet_id);
        $trajet->nombre_de_place_disponible -= $request->nbr_place;
        $trajet->update();

        SendConfirmReservationjob::dispatch(Auth::user(), $trajet, $reservation, User::find($trajet->user_id))->onQueue('ReservationEmail');

        return  response()->json(['message' => 'reservation cree']);
    }

    public function Reservations()
    {
        $reservations = Reservation::with('trajet')->where('user_id', Auth::user()->id)->simplePaginate(30);

        return response()->json($reservations);
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
