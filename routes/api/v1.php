<?php

use App\Http\Controllers\api\v1\AdditionCategoryController;
use App\Http\Controllers\api\v1\AdditionController;
use App\Http\Controllers\api\v1\AddressesController;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\CountriesController;
use App\Http\Controllers\api\v1\MealController;
use App\Http\Controllers\api\v1\TranslateController;
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

Route::get('countries', [CountriesController::class, 'index'])->middleware('set_lang');

Route::group(['prefix' => 'auth' ,'middleware' => [ 'set_lang'] ], function () {
    Route::post('login', [AuthController::class, 'login']);//->middleware('guest:sanctum');
    Route::post('register', [AuthController::class, 'register']);//->middleware('guest:sanctum');
    Route::post('social-login', [AuthController::class, 'social_login']);//->middleware('guest:sanctum');
    Route::post('forget-password', [AuthController::class, 'forget_password']);//->middleware('guest:sanctum');
    Route::post('reset-password', [AuthController::class, 'reset_password']);//->middleware('guest:sanctum');
});


Route::group(['middleware' => ['auth:sanctum' , 'set_lang']], function () {


    Route::group(['prefix' => 'auth'], function () {
        Route::post('update-profile', [AuthController::class, 'update_profile']);
        Route::post('change-password', [AuthController::class, 'change_password']);
        Route::post('mobile-verified', [AuthController::class, 'mobile_verified']);
        Route::post('online-status', [AuthController::class, 'online_status']);

    });

    Route::group(['prefix' => 'addresses'], function () {
        Route::post('create', [AddressesController::class, 'store']);
        Route::get('list', [AddressesController::class, 'list']);
        Route::get('get', [AddressesController::class, 'get']);
        Route::post('delete', [AddressesController::class, 'delete']);
        Route::post('update', [AddressesController::class, 'update']);
    });

    Route::group(['prefix' => 'translate'], function () {
        Route::get('list', [TranslateController::class, 'getAllTranslate']);
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('list', [CategoryController::class, 'getCategory']);
    });


    Route::group(['prefix' => 'maker'], function () {
        Route::group(['prefix' => 'additions-categories'], function () {
            Route::post('create', [AdditionCategoryController::class, 'store']);
            Route::get('list', [AdditionCategoryController::class, 'list']);
            Route::get('get', [AdditionCategoryController::class, 'get']);
            Route::post('delete', [AdditionCategoryController::class, 'delete']);
            Route::post('update', [AdditionCategoryController::class, 'update']);
        });

        Route::group(['prefix' => 'additions'], function () {
            Route::post('create', [AdditionController::class, 'store']);
            Route::get('list', [AdditionController::class, 'list']);
            Route::post('delete', [AdditionController::class, 'delete']);
            Route::post('update', [AdditionController::class, 'update']);
        });



        Route::group(['prefix' => 'meal'], function () {
            Route::get('gen-code', [MealController::class, 'gen_code']);
            Route::post('create', [MealController::class, 'store']);
            Route::get('get-accessories', [MealController::class, 'accessories']);
            Route::get('get', [MealController::class, 'get']);
            Route::get('list', [MealController::class, 'list']);
            Route::post('delete', [MealController::class, 'delete']);
            Route::post('update', [MealController::class, 'update']);
        });











    });


});
