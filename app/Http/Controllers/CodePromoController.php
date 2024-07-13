<?php

namespace App\Http\Controllers;

use App\Models\CodePromo;
use Illuminate\Http\Request;

class CodePromoController extends Controller
{
    public function index($reservationId)
    {
        $codes = CodePromo::where('reservation_id', $reservationId)->first();

        return response()->json($codes);
    }

    public function validated(Request $request,$reservationId){
        $validated = CodePromo::where('reservation_id', $reservationId)
        ->where("code_promo",$request->code_promo)
        ->first();

        if($validated) return response()->json(['message'=>true]);
        return response()->json(['message'=>false]);
    }

    public function UpdatedEtat(Request $request,$reservationId)
    {
        CodePromo::where('reservation_id', $reservationId)->update(['etat'=>$request->etat]);
        return response()->json(['message'=>'etat mis a jour']);
    }

    public function DeletedCodePromo($id)
    {
        CodePromo::where('reservation_id', $id)->delete();
        return response()->json(['message'=>'etat supprimme']);
    }
}
