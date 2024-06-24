<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'montant',
        'trajet_id',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function trajet(){
        return $this->belongsTo(Trajet::class,'trajet_id');
    }
}