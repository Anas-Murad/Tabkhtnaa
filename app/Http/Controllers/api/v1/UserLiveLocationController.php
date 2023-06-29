<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLiveLocationRequest;
use App\Models\UserLiveLocation;

class UserLiveLocationController extends Controller
{

    public function __invoke(UserLiveLocationRequest $request)
    {

        return  $request->validated() ;

        return UserLiveLocation::create($request->validated());
    }

}
