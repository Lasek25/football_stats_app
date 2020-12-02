<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;
use Carbon\Carbon;

class MatchController extends Controller
{
    public function index()
    {
        $matches = Match::join('teams_in_matches', 'matches.id', '=', 'teams_in_matches.match_id')
        ->join('teams_in_competitions', 'teams_in_matches.teams_in_competition_id', '=', 'teams_in_competitions.id')
        ->join('teams', 'teams_in_competitions.team_id', '=', 'teams.id')
        ->select('date','round','flashscore_id', 'name', 'goals')
        ->where('teams_in_competitions.competition_id', '=', 1)
        ->whereDate('matches.date', '<', today()->toDateString())
        ->orderBy('matches.date', 'desc')
        ->orderBy('matches.id', 'desc')
        ->get();
        return $matches;
    }
}
