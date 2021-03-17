<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class Results extends ObjectType
{
    public function __construct()
    {
        $config = [
            "name" => "Results",
            "fields" => [
                // "matchesQuantity" => Type::int(),
                // "pointsSum" => Type::int(),
                "points" => Type::listOf(Type::int()),
                "goalsScored" => Type::listOf(Type::int()),
                "goalsAgainst" => Type::listOf(Type::int()),
                "corners" => Type::listOf(Type::int()),
                "yellowCards" => Type::listOf(Type::int()),
                "redCards" => Type::listOf(Type::int()),
                "fouls" => Type::listOf(Type::int()),
                "offsides" => Type::listOf(Type::int()),
                "shotsOnGoal" => Type::listOf(Type::int()),
            ],
        ];
        parent::__construct($config);
    }
}