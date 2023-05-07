<?php

use App\Http\Controllers\api\v1\AddressesController;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\CountriesController;
use App\Http\Controllers\api\v1\TranslateController;
use App\Http\Controllers\api\v1\SendSMSController;
use App\Http\Controllers\api\v1\ContentController;
use App\Http\Controllers\api\v1\ComplaintController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('countries', [CountriesController::class, 'index']);


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);//->middleware('guest:sanctum');
    Route::post('register', [AuthController::class, 'register']);//->middleware('guest:sanctum');
    Route::post('social-login', [AuthController::class, 'social_login']);//->middleware('guest:sanctum');
    Route::post('forget-password', [AuthController::class, 'forget_password']);//->middleware('guest:sanctum');
    Route::post('reset-password', [AuthController::class, 'reset_password']);//->middleware('guest:sanctum');
});


Route::group(['middleware' => 'auth:sanctum'], function () {


    Route::group(['prefix' => 'auth'], function () {
        Route::post('send-sms' , [SendSMSController::class , 'sendSms']);
        Route::post('update-profile', [AuthController::class, 'update_profile']);
        Route::post('change-password', [AuthController::class, 'change_password']);
        Route::post('mobile-verified', [AuthController::class, 'mobile_verified']);
        Route::post('online-status', [AuthController::class, 'online_status']);
        Route::get('term-and-condition', [ContentController::class, 'term_and_condition']);
        Route::post('complete-register', [AuthController::class, 'completeRegister']);
    });


    Route::group(['prefix' => 'addresses'], function () {
        Route::post('create', [AddressesController::class, 'store']);
        Route::get('list', [AddressesController::class, 'list']);
        Route::get('get', [AddressesController::class, 'get']);
        Route::get('delete', [AddressesController::class, 'delete']);
        Route::post('update', [AddressesController::class, 'update']);
    });

    Route::group(['prefix' => 'translate'], function () {
        Route::get('list', [TranslateController::class, 'getAllTranslate']);
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('list', [CategoryController::class, 'getCategory']);
    });

    Route::group(['prefix' => 'complaint'], function () {
        Route::get('', [ComplaintController::class, 'getComplaint']);
        Route::post('create', [ComplaintController::class, 'createComplaint']);
    });

    Route::post('galleries-maker', [AuthController::class, 'galleriesMaker']);
});
