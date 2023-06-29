<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\auth\ChangePasswordRequest;
use App\Http\Requests\api\v1\auth\ForgetPasswordRequest;
use App\Http\Requests\api\v1\auth\GalleryRequest;
use App\Http\Requests\api\v1\auth\LoginRequest;
use App\Http\Requests\api\v1\auth\MobileVerifiedRequest;
use App\Http\Requests\api\v1\auth\OnlineStatusRequest;
use App\Http\Requests\api\v1\auth\RegisterRequest;
use App\Http\Requests\api\v1\auth\ResetPasswordRequest;
use App\Http\Requests\api\v1\auth\SocialLoginRequest;
use App\Http\Requests\api\v1\auth\UpdateProfileRequest;
use App\Http\Requests\api\v1\auth\UploadDocumentsRequest;
use App\Models\Document;
use App\Models\Gallery;
use App\Models\User;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    use HelperTrait;




    public function reset_password(ResetPasswordRequest $request)
    {
        $user =  User::find($request->user_id) ;
        if (!$user)
            return $this->returnError(__('messages.user_not_found'));


        $password_resets =    DB::table('password_resets')->select('*')->where("user_id" ,$user->id)->first();

        if (!$password_resets ||  $password_resets->token !=  $request->reset_password_token)
            return $this->UN_AUTHORIZED();

        $dbTime = Carbon::parse($password_resets->created_at) ;
        $nowTime = Carbon::now();
        $endTime = $dbTime->addMinutes(15);

        if ($endTime->gte($nowTime)){
            DB::table('password_resets')->where("user_id" ,$user->id)->delete();
        }else{
            return $this->returnError(__('messages.reset_token_expired'));
        }
        $user->update(['password' => bcrypt($request->new_password)]);
        $user->ApiCreateToken();
        return $this->returnDataArray($user);
    }

    public function forget_password(ForgetPasswordRequest $request)
    {

         $user = User:: where($request->validated())->where('source', 'normal')->first();
        if (!$user)
            return $this->returnError(__('messages.user_not_found'));

        $time = Carbon::now();
        $token = Str::random(64);

        DB::table('password_resets')->where("user_id" ,$user->id)->delete();
        DB::table('password_resets')->insert([
            "user_id" =>$user->id,
            'token' => $token,
            'created_at' => $time
        ]);
        $endTime = $time->addMinutes(15);
        return $this->returnDataArray(
            [
                "user_id"=>$user->id,
                "mobile"=>$user->mobile,
                "reset_password_token"=>$token,
                "reset_password_token_valid_to"=>$endTime->format('Y-m-d H:i:s'),
                "country_code"=>$user->country_code,
            ]
        );
    }




    public function online_status(OnlineStatusRequest $request)
    {
         $user = $request->user();
        if (!$user)
            return $this->returnError(__('messages.user_not_found'));
        $user->update([
            "online_status" => $request->online_status
        ]);
        //        $user->ApiCreateToken();
        return $this->returnDataArray($user);
    }



    public function mobile_verified(MobileVerifiedRequest $request)
    {
         $user = $request->user();
        if (!$user)
            return $this->returnError(__('messages.user_not_found'));
        $user->update([
            "mobile_verified" => 1
        ]);
        //        $user->ApiCreateToken();
        return $this->returnDataArray($user);
    }





    public function change_password(ChangePasswordRequest $request)
    {
        $user = $request->user();
        $user->update(['password' => bcrypt($request->password)]);
//        $user->ApiCreateToken();
        return $this->returnDataArray($user);
    }


    public function update_profile(UpdateProfileRequest $request)
    {

        $user = $request->user();
        $data = $request->safe()->except( 'profile_image');
        if ($request->hasFile('profile_image')) {
            $temp = $user->getAttributes()['profile_image'];
            if ($temp)
                Storage::delete($temp);
            $data['profile_image'] = $this->saveImage($request->profile_image, 'uploads/profile');
        }

        if ($request->filled('mobile')){
            $data['mobile_verified'] = false ;
        }


        $user->update($data);
//        $user->ApiCreateToken();
        return $this->returnDataArray($user);
    }

    public function social_login(SocialLoginRequest $request)
    {
        $user = User::where('udid', $request->udid)->first();
        if ($user) {
            $user->tokens()->delete();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            $user->access_token = $tokenResult;
            return $this->returnDataArray($user);
        }

        $data = $request->safe()->except('profile_image_url');
        if ($request->filled('profile_image_url')) {
            $data['profile_image'] = $this->saveImageUrl($request->profile_image_url, 'uploads/profile');
        }

        $data['username'] = null;
        $user = User::create($data);
        $user->refresh();
        $user->ApiCreateToken();
        return $this->returnDataArray($user);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->safe()->except('password', 'password_confirmation', 'profile_image', 'profile_image_url');
        $data['password'] = bcrypt($request->password);
        $data['username'] = null;
        $data['source'] = 'normal';
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $this->saveImage($request->profile_image, 'uploads/profile');
        }

        if ($request->type =='client'){
            $data['account_status'] ='pending' ;
        }
        $user = User::create($data);
        $user->ApiCreateToken();
        return $this->returnDataArray($user);
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->validated())) {
                return $this->returnError(__('messages.wrong_user_password'));
            }
            $user = auth()->user();
            $user->load('galleryKitchen' , 'documents');
            if (!Hash::check($request->password, $user->password, [])) {
                return $this->returnError(__('messages.wrong_user_password'));
            }
            $user->ApiCreateToken();
            return $this->returnDataArray($user);
        } catch (Exception $error) {
            return $this->returnError($error, 500, 500);
        }
    }

    public function upload_documents(UploadDocumentsRequest $request)
    {
        // reviewed by abdelrahman
        $data = [];
        if ($request->hasFile('front_id_image')) {
            $data[] = [
                'file' => $this->saveImage($request->front_id_image, 'uploads/documents'),
                'user_id' => Auth::id(),
                'type' => 'front_id_image',
                'created_at' => Carbon::now(),
            ];
        }
        if ($request->hasFile('background_id_photo')) {
            $data[] = [
                'file' => $this->saveImage($request->background_id_photo, 'uploads/documents'),
                'user_id' => Auth::id(),
                'type' => 'background_id_photo',
                'created_at' => Carbon::now(),
            ];
        }
        if ($request->hasFile('no_criminal_record')) {
            $data[] = [
                'file' => $this->saveImage($request->no_criminal_record, 'uploads/documents'),
                'user_id' => Auth::id(),
                'type' => 'no_criminal_record',
                'created_at' => Carbon::now(),
            ];
        }
        if ($request->hasFile('leave_diseases')) {
            $data[] = [
                'file' => $this->saveImage($request->leave_diseases, 'uploads/documents'),
                'user_id' => Auth::id(),
                'type' => 'leave_diseases',
                'created_at' => Carbon::now(),
            ];
        }
        if ($request->hasFile('practicing_profession')) {
            $data[] = [
                'file' => $this->saveImage($request->practicing_profession, 'uploads/documents'),
                'user_id' => Auth::id(),
                'type' => 'practicing_profession',
                'created_at' => Carbon::now(),
            ];
        }
        if ($request->hasFile('stool_examination')) {
            $data[] = [
                'file' => $this->saveImage($request->stool_examination, 'uploads/documents'),
                'user_id' => Auth::id(),
                'type' => 'stool_examination',
                'created_at' => Carbon::now(),
            ];
        }
        if ($request->hasFile('urine_test')) {
            $data[] = [
                'file' => $this->saveImage($request->urine_test, 'uploads/documents'),
                'user_id' => Auth::id(),
                'type' => 'urine_test',
                'created_at' => Carbon::now(),
            ];
        }
        if ($request->hasFile('blood_test')) {
            $data[] = [
                'file' => $this->saveImage($request->blood_test, 'uploads/documents'),
                'user_id' => Auth::id(),
                'type' => 'blood_test',
                'created_at' => Carbon::now(),
            ];
        }
        if ($request->hasFile('driving_license')) {
            $data[] = [
                'file' => $this->saveImage($request->driving_license, 'uploads/documents'),
                'user_id' => Auth::id(),
                'type' => 'driving_license',
                'created_at' => Carbon::now(),
            ];
        }
        if ($request->hasFile('car_trunk_image')) {
            $data[] = [
                'file' => $this->saveImage($request->car_trunk_image, 'uploads/documents'),
                'user_id' => Auth::id(),
                'type' => 'car_trunk_image',
                'created_at' => Carbon::now(),
            ];
        }
        Document::insert($data);
        $documents=Document::whereUserId(\auth()->id())->get() ;
        return $this->returnDataArray($documents);

    }

    public function kitchenImages(GalleryRequest $request)
    {

        // reviewed by abdelrahman
        $imagesArray = [];
        if ($request->hasFile('images')) {
            foreach ($request->images as  $image) {
                $item = [];
                $item['image'] = $this->saveImage($image, 'uploads/gallery');
                $item['user_id'] = Auth::id();
                $item['type'] = 'kitchen';
                $imagesArray[] = $item;
            }
            Gallery::insert($imagesArray);
        }
        $documents=Gallery::whereUserId(\auth()->id())->whereType('kitchen')->get() ;
        return $this->returnDataArray($documents);
    }
}
