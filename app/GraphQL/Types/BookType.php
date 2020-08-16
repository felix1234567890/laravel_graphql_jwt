<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Book;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class BookType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Book',
        'description' => 'Collection of books and details of author',
        'model' => Book::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id of a particular book'
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The title of the book'
            ],
            'author' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Name of the author of the book',
            ],
            'language' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The language which the book was written in',
            ],
            'year_published' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The year the book was published',
            ],
            'isbn' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The international standard book number for the book',
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'The user_id of the product',
            ]
        ];
    }
}
