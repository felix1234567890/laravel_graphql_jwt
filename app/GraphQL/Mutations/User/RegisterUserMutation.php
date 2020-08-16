<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\Models\User;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class RegisterUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'registerUser',
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::nonNull(Type::string()),
                ],
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
        $user = new User();
        $args['password'] = bcrypt($args['password']);
        $user->fill($args);
        $user->save();
        return $user;
    }
}
