<?php

namespace App\Http\Controllers;

use App\Mail\wallets\WalletRetraitMail;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

            $transaction = new Transaction();
            $transaction->libelle = "retraint d'argent";
            $transaction->date = Carbon::now();
            $transaction->montant = $request->montant;
            $transaction->balance = $wallet->montant - $request->montant;
            $transaction->wallet_id = $wallet->id;
            $transaction->status = 'debit';
            $transaction->save();

            Mail::to(User::find($wallet->user_id)->email)
            ->send(new WalletRetraitMail(Auth::user(), $transaction));
        }



        return response()->json(['message' => 'retrait effectué']);
    }

    
}
