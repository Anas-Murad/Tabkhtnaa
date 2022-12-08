<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\auth\LoginRequest;
use App\Http\Requests\api\v1\auth\RegisterRequest;
use App\Http\Requests\api\v1\auth\SocialLoginRequest;
use App\Models\User;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    use HelperTrait ;



    public function social_login(SocialLoginRequest $request)
    {


        $user = User::where('udid', $request->udid)->first();

        if ($user) {
            $user->tokens()->delete();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            $user->access_token= $tokenResult ;
            return $this->returnDataArray($user);
        }

        $data = $request->safe()->except(  'profile_image_url') ;
        if ($request->filled('profile_image_url')) {
            $data['profile_image'] = $this->saveImageUrl($request->profile_image_url, 'profile');
        }


        $data['username'] = null ;
        $user = User::create($data);
        $user->refresh();
        $user->ApiCreateToken() ;
        return $this->returnDataArray($user);
    }


    public function register(RegisterRequest $request)
    {


               $data = $request->safe()->except(  'password' , 'password_confirmation' , 'profile_image', 'profile_image_url') ;
        $data['password'] =  bcrypt($request->password) ;
        $data['username'] = null ;
        $data['source'] = 'normal' ;

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $this->saveImageUrl($request->profile_image, 'profile');
        }



        $user =  User::create($data);
        $user->ApiCreateToken() ;
        return  $this->returnDataArray($user) ;
    }



    public function login(LoginRequest $request)
    {

        try {



            if (!Auth::attempt($request->validated())) {
                return $this->returnError(__('messages.wrong_user_password'));
            }

             $user =  auth()->user();

            if ( ! Hash::check($request->password, $user->password, [])) {
                return $this->returnError(__('messages.wrong_user_password'));
            }

            $user->ApiCreateToken() ;
            return  $this->returnDataArray($user) ;
        } catch (Exception $error) {
            return $this->returnError($error  ,500,500);
        }

    }




}
