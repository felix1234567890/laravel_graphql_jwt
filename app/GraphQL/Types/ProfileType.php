<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Profile;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProfileType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Profile',
        'model' =>Profile::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id of a particular Profile'
            ],
            'filename' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the profile picture'
            ],
            'filePath' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The filepath of the profile picture'
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'The user_id of the product',
            ]
        ];
    }
}
