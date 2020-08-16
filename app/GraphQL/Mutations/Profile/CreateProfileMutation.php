<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Profile;

use App\Models\Profile;
use Closure;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateProfileMutation extends Mutation
{
    protected $attributes = [
        'name' => 'uploadFile',
       
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
                'rules' => ['required',],
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
            $file = $args['profilePicture'][0];
            if(!strstr($file->getMimeType(),'image')){
                throw new BadRequestException("You are allowed to upload only images");
            }
            $profileExists = Profile::where('user_id',$this->auth['id'])->first();
            if($profileExists){
                throw new BadRequestException("You already have profile picture. If you want to change it please use upload mutation");
            }
            $imageName = $this->auth['id'].'-'.$file->getClientOriginalName();
            $filePath = $file->move(public_path('images'), $imageName);
            $profile = new Profile();
            $profile->filename = $imageName;
            $profile->user_id = $this->auth['id'];
            $profile->filePath = $filePath;
            $profile->save();
        return $profile;
    }
}
