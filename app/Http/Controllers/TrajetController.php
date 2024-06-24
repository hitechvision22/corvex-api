<?php

namespace App\Http\Controllers;

use App\Jobs\Trajet\SendInfoTrajetjob;
use App\Jobs\Trajet\UpdateEtatTrajetjob;
use App\Models\Piece;
use App\Models\Trajet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrajetController extends Controller
{
    public function index()
    {
        $trajet = Trajet::orderBy('created_at','DESC')->simplePaginate(30);
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
        $trajet->prix = $request->prix;
        $trajet->nombre_de_place = $request->nombre_place;
        $trajet->nombre_de_place_disponible = $request->nombre_place;
        $trajet->user_id = Auth::user()->id;
        $trajet->mode_de_paiement = 'OM/MOMO';
        // verifions s'il possede tout les pieces
        if ($user->vehicule || $user->cni) $trajet->etat = 'Actif';
        $trajet->save();

        SendInfoTrajetjob::dispatch($trajet, Auth::user())->onQueue('TrajetEmail');
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
        Trajet::find($id)->update(['etat' => request('etat')]);

        UpdateEtatTrajetjob::dispatch($id)->onQueue('TrajetEmail');
        return response()->json(['message' => 'etat du trajet mis a jour']);
    }

    public function destroy($id)
    {
        Trajet::findOrFail($id)->delete();
        return response()->json(['message' => 'trajet supprime']);
    }


    public function search(Request $request)
    {
        // foguengcyrille@gmail.com
        /**
         * request => ville_destination/ville_depart, heure de depart
         * */

        $trajets = Trajet::where('ville_destination', 'LIKE', '%' . $request->ville_destination . '%')
            ->orWhere('ville_depart', 'LIKE', '%' . $request->ville_depart . '%')
            ->simplePaginate(30);

        return response()->json($trajets);
    }
}
