<?php

namespace App\GraphQL\Queries;

// use App\Models\TeamsInCompetition;
use App\Models\TeamsInMatch;

class AvgQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $results = collect();
        // month (after ending the season and before starting the new one) necessary for the correct year selection
        $month = 7;
        if (now()->month > $month) {
            $year = now()->year . '/' . now()->addYear()->year;
        }
        else {
            $year = now()->addYears(-1)->year . '/' . now()->year;
        }
        // $allTeamsInMatch = TeamsInMatch::where('teams_in_matches.updated_at','!=',null)->join('matches', 'teams_in_matches.match_id', '=', 'matches.id')->orderBy('matches.date','desc');
        $allTeamsInMatch = TeamsInMatch::where('teams_in_matches.updated_at','!=',null)
            ->join('matches', 'teams_in_matches.match_id', '=', 'matches.id')
            ->join('teams_in_competitions', 'teams_in_matches.teams_in_competition_id', '=', 'teams_in_competitions.id')
            ->select('teams_in_matches.*','teams_in_competitions.competition_id','teams_in_competitions.season')
            ->where('teams_in_competitions.season','=',$year)
            ->orderBy('teams_in_matches.teams_in_competition_id','asc')
            ->latest('matches.date');

        if($args['competition'] != 0) {
            $allTeamsInMatch = $allTeamsInMatch
                ->where('teams_in_competitions.competition_id','=',$args['competition']);
        }
        $allTeamsInMatch = $allTeamsInMatch->get();
        if($args['matchesCount'] == 0)
            return $allTeamsInMatch;
        else {
            $counter = 0;
            $tmpTeam = $allTeamsInMatch[0]->teamsInCompetition->id;
            foreach($allTeamsInMatch as $teamInMatch) {
                if($teamInMatch->teamsInCompetition->id != $tmpTeam) {
                    $counter = 0;
                    $tmpTeam = $teamInMatch->teamsInCompetition->id;
                }
                if($counter < $args['matchesCount']) {
                    $results->push($teamInMatch);
                    $counter++;
                }
            }
            return $results;
        }
    }
}
