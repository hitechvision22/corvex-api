<?php

namespace App\Http\Controllers;

use App\Models\Piece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PieceController extends Controller
{
    // lister tout les pieces
    public function index()
    {
        $pieces = Piece::simplePaginate(40);
        return response()->json($pieces);
    }

    // lister les pieces d'un user
    public function Mypiece($id)
    {
        $pieces = Piece::with('vehicule')->where('user_id', $id)->get();
        return response()->json($pieces);
    }

    // enregistrer une piece
    public function StorePiece(Request $request)
    {
        $inter = Piece::where("nom", $request->nom)->where('user_id', Auth::user()->id)->first();
        if ($inter) {
            return response()->json(['message' => 'Piece existe deja']);
        } else {
            $piece = new Piece();
            $piece->nom = $request->nom;
            $piece->date_expiration = $request->date_expiration;
            if ($request->vehicule_id) $piece->vehicule_id = $request->vehicule_id;
            $piece->user_id = Auth::user()->id;

            if ($request->hasFile('image1') && $request->hasFile('image2')) {
                $file1 = $request->file('image1');
                $file2 = $request->file('image2');

                $extension = $file1->getClientOriginalExtension();
                $extension = $file2->getClientOriginalExtension();

                $newfilename1 = 'image1' . "-" . $request->nom . "-" . $extension;
                $newfilename2 = 'image2' . "-" . $request->nom . "-" . $extension;


                $file1->storeAs('images', $newfilename1);
                $file2->storeAs('images', $newfilename2);

                $piece->image = json_encode([$newfilename1, $newfilename2]);
            }

            $piece->save();
            return response()->json($piece);
        }
    }

    // supprimer une piece
    public function DeletePiece($id)
    {
        Piece::find($id)->delete();
        return response()->json(['message' => 'piece supprime']);
    }

    // mettre a jour piece
    public function UpdatePiece(Request $request)
    {
        $piece = Piece::where("nom", request('nom'))->where('user_id', Auth::user()->id)->first();
        $piece->nom = $request->nom;
        $piece->date_expiration = $request->date_expiration;
        if ($request->id_vehicule) $piece->id_vehicule = $request->id_vehicule;
        $piece->user_id = Auth::user()->id;

        if ($request->hasFile('image1') && $request->hasFile('image2')) {

            File::delete(public_path() . "images/" . json_decode($piece->image)[0]);
            File::delete(public_path() . "images/" . json_decode($piece->image)[1]);

            $file1 = $request->file('image1');
            $file2 = $request->file('image2');

            $extension = $file1->getClientOriginalExtension();
            $extension = $file2->getClientOriginalExtension();

            $newfilename1 = 'image1' . "-" . $request->nom . "-" . $extension;
            $newfilename2 = 'image2' . "-" . $request->nom . "-" . $extension;

            $file1->move(public_path('images'), $newfilename1);
            $file2->move(public_path('images'), $newfilename2);

            $piece->image = json_encode([$newfilename1, $newfilename2]);
        }

        $piece->update();

        return response()->json($piece);
    }

    public function GetPiece()
    {
        $inter = Piece::where("nom", request('nom'))->where('user_id', Auth::user()->id)->first();
        if ($inter) return response()->json($inter);
        return response()->json(['message' => "not existe"], 422);
    }
}
