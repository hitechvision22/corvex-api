<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Trajet extends Model
{
    use HasFactory;

    protected $fillable = [
        'ville_depart',
        'point_rencontre',
        'ville_destination',
        'point_destination',
        'date_depart',
        'heure_depart',
        'prix',
        'Nombre_de_place',
        'nombre_de_place_disponible',
        'Mode_de_paiement',
        'etat', // actif/deactif
        'status', // initie => en cours => termine / annuler
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toSearchableArray(){
        return [
            'ville_depart'=>$this->ville_depart,
            'point_rencontre'=>$this->point_rencontre,
            'ville_destination'=>$this->ville_destination,
            'point_destination'=>$this->point_destination,
            'date_depart'=>$this->date_depart,
        ];
    }


}
