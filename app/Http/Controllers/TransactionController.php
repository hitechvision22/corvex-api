<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // les transactions de l'utilisateur en ligne
    public function index()
    {
        $MyTransactions = Transaction::with('trajet')->where('user_id', Auth::user()->id)
            ->simplePaginate(30);

        return response()->json($MyTransactions);
    }

    public function AllTransactions()
    {
        $AllTransactions = Transaction::with('reservation', 'wallet')->get();

        foreach ($AllTransactions as $Transaction) {
           $wallet = $Transaction->wallet;
           $user = User::find($wallet->user_id);
           $Transaction['user'] = $user;
        }

        return response()->json($AllTransactions);
    }

    public function CreditTransaction()
    {
        $wallet = Wallet::where("user_id",Auth::user()->id)->first();

        $AllTransactions = Transaction::with('reservation', 'wallet')->where('wallet_id',$wallet->id)->where('status','credit')->get();

        return response()->json($AllTransactions);
    }

    public function DebitTransaction()
    {
        $wallet = Wallet::where("user_id",Auth::user()->id)->first();

        $AllTransactions = Transaction::with('reservation', 'wallet')->where('wallet_id',$wallet->id)->where('status','!=','credit')->get(30);

        return response()->json($AllTransactions);
    }

    public function DetailTransaction($id){
        $Transaction = Transaction::with('reservation', 'wallet')->find($id);
        $Transaction->reservation->trajet;
        return response()->json($Transaction);
    }

    public function DeleteTransaction($id){
        Transaction::find($id)->delete();
        return response()->json(['message' => 'Transaction supprimée']);
    }
}
