<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'f_name', 
        'l_name', 
        'email', 
        'position', 
        'height', 
        'weight', 
        'age', 
        'experience', 
        'college', 
        'avatar', 
        'team_id'
    ];

    // A player belongs to a team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
