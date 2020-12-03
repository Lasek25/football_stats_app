<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;
use Carbon\Carbon;

class MatchController extends Controller
{
    public function index()
    {
        $matches =  Match::whereDate('date', '<', today()->toDateString())->get()->map(function ($match) {
            $competition = $match->teamsInMatches[0]->teamsInCompetition->competition;
            return [
                'id' => $match->id,
                'competition' =>
                    [
                        'id' => $competition->id,
                        'name' => $competition->name,
                        'country' => $competition->competitionsType->country,
                        'competitionsTypeId' => $competition->competitions_type_id,
                        'type' => $competition->competitionsType->name
                    ],
                'round' => $match->round,
                'date' => date('Y-m-d H:i', strtotime($match->date)),
                'teams' => $match->teamsInMatches->map(function ($team) {
                    return [
                        'id' => $team->id,
                        'goals' => $team->goals,
                        'name' => $team->teamsInCompetition->team->name
                    ];
                })
            ];
        });

        return ['matches' => $matches];
    }
}
