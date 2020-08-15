<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Book;

use App\Book;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class BooksQuery extends Query
{
    protected $attributes = [
        'name' => 'books',
    ];
   
    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Book'));
    }
    public function args():array
    {
        return [
            'limit' => [
                'name' => 'limit',
                'type' => Type::int()
            ],
        ];
    }


    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if(isset($args['limit'])){
            return Book::limit($args['limit'])->get();
        }
        $fields = $getSelectFields();
        $with = $fields->getRelations();
        $books = Book::with($with);
        return $books->get();
    }
}
