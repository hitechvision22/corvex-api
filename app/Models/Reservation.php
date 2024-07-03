<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'avis',
        'trajet_id',
        'user_id',
        'nbr_place',
        'status',
    ];

    public function trajet()
    {
        return $this->belongsTo(Trajet::class, 'trajet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
