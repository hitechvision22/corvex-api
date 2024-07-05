<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom_piece',
        'date_expiration',
        'image',
        'user_id',
        'vehicule_id',
    ];
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class, 'id_vehicule');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
