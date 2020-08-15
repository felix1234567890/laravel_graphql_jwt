<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\User;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type; 
use Rebing\GraphQL\Support\Facades\GraphQL; 

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'model' => User::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id of the User',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the User',
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['email'],
                'description' => 'Email of the User',
            ],
            'books' => [
                'type' => Type::listOf(GraphQL::type('Book')),
                'description' => 'User books',
            ]
        ];
    }
}
