<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    use HelperTrait ;

    public function login(Request $request)
    {

        return $request->all() ;
    }




}
