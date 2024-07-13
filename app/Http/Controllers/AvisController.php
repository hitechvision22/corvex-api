<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    public function StoreAvis(Request $request){
        Avis::create([
            'note'=>$request->note,
            'libelle'=>$request->libelle,
            'user_id'=>Auth::user()->id
        ]);

        return response()->json(['message'=>'avis enregistrer']);
    }

    public function DeletedAvis($id){
        Avis::find($id)->delete();

        return response()->json(['message'=>'avis supprime']);
    }
}
