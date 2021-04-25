<?php

namespace App\GraphQL\Mutations;

use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\Logout as MutationsLogout;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Joselfonseca\LighthouseGraphQLPassport\Events\UserLoggedOut;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Logout extends MutationsLogout
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        if (! Auth::guard('api')->check()) {
            throw new AuthenticationException('Not Authenticated', 'Not Authenticated');
        }
        $user = Auth::guard('api')->user();
        // revoke user's token
        Auth::guard('api')->user()->token()->revoke();

        event(new UserLoggedOut($user));

        return [
            'status'  => 'TOKEN_REVOKED',
            'message' => __('Wylogowałeś się poprawie'),
        ];
    }
}
