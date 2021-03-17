<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AvgResult extends ObjectType
{
    public function __construct()
    {
        $config = [
            "name" => "AvgResult",
            "fields" => [
                "avgGoals" => Type::float(),
                "avgCorners" => Type::float(),
                "avgYellowCards" => Type::float(),
                "avgRedCards" => Type::float(),
                "avgFouls" => Type::float(),
                "avgOffsides" => Type::float(),
                "avgShotsOnGoal" => Type::float(),
            ],
        ];
        parent::__construct($config);
    }
}