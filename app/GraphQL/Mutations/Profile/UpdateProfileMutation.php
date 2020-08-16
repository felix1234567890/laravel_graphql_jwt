<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Profile;

use App\Models\Profile;
use Closure;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateProfileMutation extends Mutation
{
    protected $attributes = [
        'name' => 'profile/UpdateProfile',
        'description' => 'A mutation'
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
        $profile  = Profile::where('user_id',$this->auth['id'])->first();
        if(!$profile){
            return false;
        }
        return true;
    }


    public function type(): Type
    {
        return GraphQL::type('Profile');
    }

    public function args(): array
    {
        return [
            'profilePicture' => [
                'name' => 'profilePicture',
                'type' => GraphQL::type('Upload'),
                'rules' => ['required'],
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $profile = Profile::where('user_id',$this->auth['id'])->first();
        unlink($profile->filePath);
        $file = $args['profilePicture'][0];
        $imageName = $profile->user_id.'-'.$file->getClientOriginalName();
        $filePath = $file->move(public_path('images'), $imageName);
        $profile->filename = $imageName;
        $profile->filePath = $filePath;
        $profile->save();
        return $profile;
    }
}
