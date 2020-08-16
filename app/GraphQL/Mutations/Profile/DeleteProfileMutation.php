<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Profile;

use App\Models\Profile;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteProfileMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteProfile',
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
        $profile  = Profile::findOrFail($args['id']);
        if($profile->user_id != $this->auth['id']){
            return false;
        }
        return true;
    }

    public function type(): Type
    {
        return Type::boolean();
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required']
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $profile = Profile::findOrFail($args['id']);
        unlink($profile->filePath);
        return $profile->delete() ? true : false;
    }
}
