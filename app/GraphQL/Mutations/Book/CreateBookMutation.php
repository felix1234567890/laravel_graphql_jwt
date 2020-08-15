<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Book;

use App\Book;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateBookMutation extends Mutation
{
    private $auth;
    protected $attributes = [
        'name' => 'createBook',
    ];
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null):bool {
        try {
            $this->auth = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            $this->auth = null;
        }
        return (boolean) $this->auth;
    }

    public function type(): Type
    {
        return GraphQL::type('Book');
    }

    public function args(): array
    {
        return [
            'title' => [
                'name' => 'title',
                'type' => Type::nonNull(Type::string())
            ],
            'author' => [
                'name' => 'author',
                'type' => Type::nonNull(Type::string())
            ],
            'language' => [
                'name' => 'language',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'year_published' => [
                'name' => 'year_published',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'isbn' => [
                'name' => 'isbn',
                'type' =>  Type::nonNull(Type::string()),
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $book = new Book();
        $book->user_id = $this->auth['id'];
        $book->fill($args);
        $book->save();
        return $book;
    }
}
