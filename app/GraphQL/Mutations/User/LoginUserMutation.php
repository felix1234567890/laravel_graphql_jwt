<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;

class LoginUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'loginUser',
    ];

    public function type(): Type
    {
        return Type::string();
    }

    public function args(): array
    {
        return [
            'email' => [
                'name' => 'email',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['email']
                ],
            'password' => [
                    'name' => 'password',
                    'type' => Type::nonNull(Type::string()),
                    ],
                   ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return Auth::attempt(['email' => $args['email'],'password' => $args['password']]);
    }
}
