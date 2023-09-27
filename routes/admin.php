<?php

use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TranslateController;
use App\Http\Controllers\Admin\ComplaintsController;
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
    Route::post('logout' , [LoginController::class , 'logout'])->name('admin.logout');




    Route::get('/' , function (){
        return view('admin.layouts.app');
    })->name('admin.dashboard');


    Route::get('users/{status}/{type}' , [UserController::class , 'index'])->name('usersByStatusType')
        ->where('status', 'all|pending|active|rejected|blocked')
        ->where('type', 'client|delivery|chef');


    Route::put('users/update/{id}/status' , [UserController::class , 'update_status'])->name('users.update.status') ;
    Route::resource('users', UserController::class)->names('users');
    //translations
    Route::resource('translations', TranslateController::class);

    //complaints
    Route::resource('complaints', ComplaintsController::class);
    //profile
    Route::get('profile' , [ProfileController::class , 'edit'])->name('admin.profile.edit');
    Route::put('profile/update' , [ProfileController::class , 'update'])->name('admin.profile.update');






    Route::group([
        'prefix' => 'orders',
        'controller' =>OrdersController::class
    ], function () {

        Route::get('/{status?}/{transactionStatus?}/{userID?}' ,'index')->name('admin.orders.index')
            ->where('status', 'all|pending|confirmed|prepare|prepared|on_way|delivered|not_delivered|rejected|cancel|not_ordered')
            ->where('transactionStatus', 'all|pending|success|cancel')
            ->whereNumber('userID');

        Route::get('/{id}' ,'show')->name('admin.orders.show')->whereNumber('id');

        // Route::get('user/{userID}' ,'index')->name('admin.profile.edit');

    });



    Route::group([
        'prefix' => 'transactions',
        'controller' =>OrdersController::class
    ], function () {

        Route::get('/{id}' ,'show')->name('admin.transaction.order')->whereNumber('id');

    });










});

Route::get('t' , function (){
    return view('layouts.app');
});







