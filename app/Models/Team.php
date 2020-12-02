<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    public function favouriteTeams() 
    {
        return $this->hasMany('App\Models\FavouriteTeam');
    }

    public function teamsInCompetitions()
    {
        return $this->hasMany('App\Models\TeamsInCompetition');
    }
}
