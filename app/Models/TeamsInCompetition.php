<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamsInCompetition extends Model
{
    use HasFactory;

    public function teamsInMatches()
    {
        return $this->hasMany('App\Models\TeamsInMatch');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function competition()
    {
        return $this->belongsTo('App\Models\Competition');
    }
}
