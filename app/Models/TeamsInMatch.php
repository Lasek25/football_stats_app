<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamsInMatch extends Model
{
    use HasFactory;

    public function teamsInCompetition()
    {
        return $this->belongsTo('App\Models\TeamsInCompetition');
    }

    public function match()
    {
        return $this->belongsTo('App\Models\Match');
    }
}
