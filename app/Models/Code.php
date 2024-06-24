<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Icon(EneftyIcons.profile_bold,size: 30,color: Colors.white,)

class Code extends Model
{
    use HasFactory;
    protected $fillable = ['email','code'];
}
