<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
}
