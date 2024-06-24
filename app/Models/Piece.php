<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_vehicule',
        'carte_grise',
        'permis'
    ];
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class, 'id_vehicule');
    }


    /**
     * 
     * un utilisateur a un vehicule...
     * un vehicule a des pieces(carte grise,le permis,cni)
     * 
     * 
     * 
     * 
     * */
}
