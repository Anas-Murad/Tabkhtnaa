<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Models\Admin;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use HelperTrait;

    public function edit()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.auth.update_profile',compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->safe()->except('profile_image');
        if ($request->hasFile('profile_image')) {
            $temp = $user->getAttributes()['profile_image'];
            if ($temp) Storage::delete($temp);
            $data['profile_image'] = $this->saveImage($request->profile_image, 'uploads/profile');
        }
        $user->update($data);
        session()->flash('Success' , 'Success Update Profile. ');
        return back();
    }
}
