<?php

namespace App\GraphQL\Queries;

use App\Models\TeamsInCompetition;
use App\Models\TeamsInMatch;
use App\GraphQL\Types\Results;

class ResultsQuery
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
        $allTeamsInCompetition = TeamsInCompetition::with('teamsInMatches.match')->where('teams_in_competitions.season','=',$year)
            ->orderBy('teams_in_competitions.id','asc');

        $args['competition'] != 0 
            ? $allTeamsInCompetition = $allTeamsInCompetition->where('teams_in_competitions.competition_id','=',$args['competition'])->get()
            : $allTeamsInCompetition = $allTeamsInCompetition->get();

        $allTeamsInMatches = TeamsInMatch::with('match')->where('updated_at', '!=', '')->get();

        foreach($allTeamsInCompetition as $team) {
            $filteredMatches = $team->teamsInMatches->filter(function ($tIMatch) {
                return $tIMatch->updated_at != '';
            })->sortByDesc('match.date');
            $args['matchesQuantity'] != 0 
                ? $filteredMatches = $filteredMatches->take($args['matchesQuantity'])
                : '';
            
            $filteredMatchesPt = $allTeamsInMatches->whereIn('match_id', $filteredMatches->map(function ($tIMatch) {
                return $tIMatch->match_id;
            }))->sortByDesc('match.date');
            $filteredMatchesPt = $filteredMatchesPt->values()->all();

            // if ($args['isMatchStats']) {
            //     $filteredMatches = TeamsInMatch::whereIn('match_id', $filteredMatches->map(function ($tIMatch) {
            //         return $tIMatch->match_id;
            //     }))->get()->sortByDesc('match.date');
            // }

            $tmpTeam = new Results();
            $tmpTeam->teamsInCompetition = $team;
            $points = collect();
            $goalsScored = collect();
            $goalsAgainst = collect();
            $corners = collect();
            $yellowCards = collect();
            $redCards = collect();
            $fouls = collect();
            $offsides = collect();
            $shotsOnGoal = collect();
            
            for ($i=0; $i < count($filteredMatchesPt); $i+=2) { 
                if(
                    (($filteredMatchesPt[$i]->goals > $filteredMatchesPt[$i+1]->goals) 
                    && ($filteredMatchesPt[$i]->teams_in_competition_id == $team->id))
                    ||
                    (($filteredMatchesPt[$i]->goals < $filteredMatchesPt[$i+1]->goals) 
                    && ($filteredMatchesPt[$i+1]->teams_in_competition_id == $team->id))
                )
                    $points->push(3);
                elseif ($filteredMatchesPt[$i]->goals == $filteredMatchesPt[$i+1]->goals)
                    $points->push(1);
                else
                    $points->push(0);
                if($filteredMatchesPt[$i]->teams_in_competition_id == $team->id) {
                    $goalsScored->push($filteredMatchesPt[$i]->goals);
                    $goalsAgainst->push($filteredMatchesPt[$i+1]->goals);
                }
                else {
                    $goalsScored->push($filteredMatchesPt[$i+1]->goals);
                    $goalsAgainst->push($filteredMatchesPt[$i]->goals);
                }
            }

            foreach($filteredMatches as $match) {
                $corners->push($match->corners);
                $yellowCards->push($match->yellow_cards);
                $redCards->push($match->red_cards);
                $fouls->push($match->fouls);
                $offsides->push($match->offsides);
                $shotsOnGoal->push($match->shots_on_goal);
            }
            // $tmpTeam->pointsSum = $points->sum();
            $tmpTeam->points = $points;
            $tmpTeam->goalsScored = $goalsScored;
            $tmpTeam->goalsAgainst = $goalsAgainst;
            $tmpTeam->corners = $corners;
            $tmpTeam->yellowCards = $yellowCards;
            $tmpTeam->redCards = $redCards;
            $tmpTeam->fouls = $fouls;
            $tmpTeam->offsides = $offsides;
            $tmpTeam->shotsOnGoal = $shotsOnGoal;
            $results->push($tmpTeam);
        }
        return $results;
    }
}
