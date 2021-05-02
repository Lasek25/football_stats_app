<?php

namespace App\GraphQL\Queries;
use App\Models\Match;

class MatchesQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        if($args['days'] < 0)
            $lastMatches = Match::whereBetween('date', [today()->addDays($args['days'])->toDateString(), today()->toDateString()])->orderBy('date', $args['orderBy'])->get();
        else
            $lastMatches = Match::whereBetween('date', [today()->toDateString(), today()->addDays($args['days']+1)->toDateString()])->orderBy('date', $args['orderBy'])->get();
        
        if($args['competition'] != 0){
            $matches = collect();
            foreach ($lastMatches as $match) {
                if ($match->teamsInMatches[0]->teamsInCompetition->competition_id == $args['competition']) {
                    $matches->push($match);
                }
            }
        }
        else
            $matches = $lastMatches;            
        
        return $matches;
    }
}
