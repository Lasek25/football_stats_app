<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;

    public function teamsInMatches()
    {
        return $this->hasMany('App\Models\TeamsInMatch');
    }
}
