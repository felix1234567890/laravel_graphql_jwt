<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Book;

use App\Book;
use Closure;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookQuery extends Query
{
    protected $attributes = [
        'name' => 'book',
    ];
    
    public function type(): Type
    {
        return GraphQL::type('Book');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required']
                ],];
            }  

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return Book::findOrFail($args['id']);
    }
    public function validationErrorMessages(array $args = []): array
{
    return [
        'required' => 'Please enter an id',
    ];
}
}
