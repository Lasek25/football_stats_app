<?php

namespace App\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateUser
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     * @param GraphQLContext|null $context
     */
    public function __invoke($_, array $args, GraphQLContext $context = null)
    {
        $user = $context->user();
        $user->name = $args['name'];
        $user->save();

        return [
            'status'  => 'NAME_UPDATED',
            'message' => __('Nazwa użytkownika została zaktualizowana'),
        ];
    }
}
