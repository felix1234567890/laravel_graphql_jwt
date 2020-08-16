<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Profile;

use App\Models\Profile;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ProfilesQuery extends Query
{
    protected $attributes = [
        'name' => 'profile/Profiles',
        'description' => 'A query'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Profile'));
    }

    public function args(): array
    {
        return [

        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $with = $fields->getRelations();
        $profiles = Profile::with($with);
        return $profiles->get();
    }
}
