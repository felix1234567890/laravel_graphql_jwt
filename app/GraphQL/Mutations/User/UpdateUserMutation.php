<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\User;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateUser',
    ];
    private $auth;
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null):bool {
        try {
            $this->auth = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            $this->auth = null;
        }
        if(! $this->auth){
            return false;
        }
       if($this->auth['id'] != $args['id']){
           return false;
       }
        return true;
    }

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int()),
                ],
            'name' => [
                'name' => 'name',
                'type' => Type::string(),
                ],
            'email' => [
                    'name' => 'email',
                    'type' => Type::string(),
                    'rules' => ['email']
                    ],
            'password' => [
                        'name' => 'password',
                        'type' => Type::string(),
                        ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    { 
        $user = User::findOrFail($args['id']);
        if(isset($args['name'])){
            $user->name = $args['name'];
        }
        if(isset($args['email'])){
            $user->email = $args['email'];
        }
        if(isset($args['password'])){
            $user->password = bcrypt($args['password']);
        }
        $user->save();
        return $user;
    }
}
