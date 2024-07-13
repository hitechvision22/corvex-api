<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodePromo extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_promo',
        'etat',
        'reservation_id'
    ];
    public function reservation(){
        return $this->belongsTo(Reservation::class,'reservation_id');
    }
}
