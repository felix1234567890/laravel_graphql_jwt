<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Book;

use App\Models\Book;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateBookMutation extends Mutation
{
    private $auth;
    protected $attributes = [
        'name' => 'updateBook',
    ];
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null):bool {
        try {
            $this->auth = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            $this->auth = null;
        }
        if(! $this->auth){
            return false;
        }
        $book  = Book::findOrFail($args['id']);
        if($book->user_id != $this->auth['id']){
            return false;
        }
        return true;
    }

    public function type(): Type
    {
        return GraphQL::type('Book');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int()),
                ],
            'title' => [
                'name' => 'title',
                'type' => Type::string()
            ],
            'author' => [
                'name' => 'author',
                'type' => Type::string()
            ],
            'language' => [
                'name' => 'language',
                'type' =>  Type::string()
            ],
            'year_published' => [
                'name' => 'year_published',
                'type' =>  Type::string()
            ],
            'isbn' => [
                'name' => 'isbn',
                'type' =>  Type::string()
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $book = Book::findOrFail($args['id']);
        if(isset($args['title'])){
            $book->title = $args['title'];
        }
        if(isset($args['author'])){
            $book->author = $args['author'];
        }
        if(isset($args['language'])){
            $book->language = $args['language'];
        }
        if(isset($args['year_published'])){
            $book->year_published = $args['year_published'];
        }
        if(isset($args['isbn'])){
            $book->isbn = $args['isbn'];
        }
        $book->save();
        return $book;
    }
    public function getAuthorizationMessage(): string
    {
        return 'You are not authorized to perform this action';
    }
}
