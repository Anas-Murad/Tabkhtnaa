<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login' , [LoginController::class , 'showLoginForm'])->name('admin.login');
Route::post('login' , [LoginController::class , 'login'])->name('login');
Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('/' , function (){
        return view('admin.layouts.app');
    })->name('admin.dashboard');
    Route::resource('users', UserController::class)->names('users');
    Route::post('logout' , [LoginController::class , 'logout'])->name('admin.logout');
});

Route::get('t' , function (){
    return view('layouts.app');
});







