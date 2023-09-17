<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Storage;
use App\DataTables\UsersDataTable;

class UserController extends Controller
{
    use HelperTrait;

    public function index(UsersDataTable $dataTable)
    {

        return $dataTable->render('admin.users.index');
    }

    public function edit()
    {
        return view('admin.auth.update_profile');
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->safe()->except('profile_image');
        if ($request->hasFile('profile_image')) {
            $temp = $user->getAttributes()['profile_image'];
            if ($temp)
                Storage::delete($temp);
            $data['profile_image'] = $this->saveImage($request->profile_image, 'uploads/profile');
        }
        $user->update($data);
        return back()->with('success', 'Success Update Profile.');
    }
}
