<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            if(Auth::guard('admin')->user()->account_status == 'inactive'){
                auth()->guard('admin')->logout();
                return back()->with('error','Sorry! Currently you are banned in this system.');
            }
            if($request->remember){
                Cache::put('remember_login',['email' => $request->email, 'password' => $request->password], 60000);
            }
            return redirect(route('admin.dashboard'));
        }
        return back()->with('error','Sorry! Credentials Mismatch.');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        return redirect(route('admin.login'));
    }
}
