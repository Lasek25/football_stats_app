<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    public function competitionsType()
    {
        return $this->belongsTo('App\Models\CompetitionsType');
    }

    public function favouriteCompetitions() 
    {
        return $this->hasMany('App\Models\FavouriteCompetition');
    }

    public function teamsInCompetitions()
    {
        return $this->hasMany('App\Models\TeamsInCompetition');
    }
}
