<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'date',
        'montant',
        'balance',
        'wallet_id',
        'reservation_id',
        'status',
    ];

    public function reservation(){
        return $this->belongsTo(Reservation::class,'reservation_id');
    }

    public function wallet(){
        return $this->belongsTo(Wallet::class,'wallet_id');
    }

}
