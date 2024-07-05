<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function MyWallet()
    {
        $wallet = Wallet::with('transactions')->where('user_id', Auth::user()->id)->get();
        return response()->json($wallet);
    }


    public function Retrait(Request $request)
    {
        $wallet = Wallet::where('user_id', Auth::user()->id)->first();
        if ($request->montant <= $wallet->montant) {
            $wallet->montant -= $request->montant;
            $wallet->update();
        }

        return response()->json(['message' => 'retrait effectué']);
    }
}
