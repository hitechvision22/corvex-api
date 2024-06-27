<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rencontre extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_client',
        'id_chauffeur',
        'status',
        'id_trajet',
        'checkChauffeur',
        'checkClient',
    ];
    public function client()
    {
        return $this->belongsTo(User::class, 'id_client');
    }
    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'id_chauffeur');
    }
    public function trajet()
    {
        return $this->belongsTo(Trajet::class, 'id_trajet');
    }
}
