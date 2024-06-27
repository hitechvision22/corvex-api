<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'montant',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id', 'id')->orderBy('id', 'DESC');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
