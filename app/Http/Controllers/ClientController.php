<?php

namespace App\Http\Controllers;

use App\Models\Trajet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $trajets = Trajet::when($request->input('ville_depart'), function ($query, $villeDepart) {
            $query->where('ville_depart', 'like', "%{$villeDepart}%");
        })->when($request->input('ville_destination'), function ($query, $villeDestination) {
            $query->where('ville_destination', 'like', "%{$villeDestination}%");
        })->when($request->input('date_depart'), function ($query, $dateDepart) {
            $query->where('date_depart', '>=', $dateDepart);
        })->where('etat', 'Actif')->SimplePaginate(30);

        return response()->json($trajets);
    }

    public function notification(){
        $user = Auth::user();
        $allNotifications = $user->notifications;
        $user->unreadNotifications->markAsRead();
    

        return response()->json([$allNotifications]);
    }
}
