<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Game, Player, User};

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'logo',
    ];

    public function gamesAsTeam1()
    {
        return $this->hasMany(Game::class, 'team1_id');
    }

    public function gamesAsTeam2()
    {
        return $this->hasMany(Game::class, 'team2_id');
    }

    // A team can have many players
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
