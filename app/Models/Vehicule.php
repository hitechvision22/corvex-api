<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;
    protected $fillable = [
        'marque',
        'modele',
        'couleur',
        'nombre_portes',
        'nombre_place',
        'etat',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
