<?php

namespace App\Http\Controllers;

use App\Jobs\vehicules\SaveVehiculeJob;
use App\Mail\vehicules\SaveVehiculeMail;
use App\Models\Piece;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VehiculeController extends Controller
{
    public function SaveVehicule(Request $request)
    {
        $vehicule = new Vehicule();
        $vehicule->marque = $request->marque;
        $vehicule->modele = $request->modele;
        $vehicule->couleur = $request->couleur;
        $vehicule->nombre_portes = $request->nombre_portes;
        $vehicule->nombre_place = $request->nombre_places;
        $vehicule->etat = $request->etat;
        $vehicule->user_id = Auth::user()->id;
        $vehicule->save();

        Mail::to(User::find(Auth::user()->id)->email)
        ->send(new SaveVehiculeMail(User::find(Auth::user()->id),$vehicule));
        return response()->json($vehicule);
    }

    public function SaveVehiculePiece(Request $request, $id)
    {
        $piece = new Piece();
        $piece->vehicule_id = $id;
        // carte grise
        if ($request->hasFile('Recto_carte_grise') && $request->hasFile('verso_carte_grise')) {
            $file1 = $request->file('Recto_carte_grise');
            $file2 = $request->file('verso_carte_grise');

            $extension = $file1->getClientOriginalExtension();
            $extension = $file2->getClientOriginalExtension();

            $newfilename1 = 'Recto_carte_grise' . '.' . $extension;
            $newfilename2 = 'verso_carte_grise' . '.' . $extension;

            $file1->move(public_path('images'), $newfilename1);
            $file2->move(public_path('images'), $newfilename2);

            $piece->carte_grise = json_encode([$newfilename1, $newfilename2]);
        }

        if ($request->hasFile('Recto_permis') && $request->hasFile('verso_permis')) {
            $file1 = $request->file('Recto_permis');
            $file2 = $request->file('verso_permis');

            $extension = $file1->getClientOriginalExtension();
            $extension = $file2->getClientOriginalExtension();

            $newfilename1 = 'Recto_permis' . '.' . $extension;
            $newfilename2 = 'verso_permis' . '.' . $extension;

            $file1->move(public_path('images'), $newfilename1);
            $file2->move(public_path('images'), $newfilename2);

            $piece->permis = json_encode([$newfilename1, $newfilename2]);
        }

        $piece->save();
        return response()->json(['message' => "piece enregistrer"], 422);
    }

    public function VehiculePiece($id)
    {
        $piece = Piece::where('vehicule_id', $id)->first();
        return response()->json($piece);
    }

    public function DeleteVehicule($id)
    {
        Vehicule::find($id)->delete();
        return response()->json(['message' => 'vehicule supprime']);
    }
}
