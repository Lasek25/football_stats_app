<?php

namespace App\GraphQL\Mutations;

use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\UpdatePassword as MutationsUpdatePassword;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Hash;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdatePassword extends MutationsUpdatePassword
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $user = $context->user();
        if (! Hash::check($args['old_password'], $user->password)) {
            throw new ValidationException([
                'password' => __('Podane hasło jest nieprawidłowe.'),
            ], 'Validation Exception');
        }
        $user->password = Hash::make($args['password']);
        $user->save();
        event(new PasswordUpdated($user));

        return [
            'status'  => 'PASSWORD_UPDATED',
            'message' => __('Hasło zostało zmienione poprawnie'),
        ];
    }
}
