<?php

use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\AddressesController;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\TranslateController;
use App\Http\Controllers\api\v1\CountriesController;
use Illuminate\Http\Request;
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
Route::post('login', [AuthController::class, 'login'])->middleware('guest:sanctum');
Route::post('register', [AuthController::class, 'register'])->middleware('guest:sanctum');
Route::post('social_login', [AuthController::class, 'social_login'])->middleware('guest:sanctum');

Route::group(['middleware' => 'auth:sanctum'] , function (){

    Route::group(['prefix' => 'addresses'] , function (){
        Route::post('create', [AddressesController::class, 'store']);
        Route::get('list', [AddressesController::class, 'list']);
        Route::get('get', [AddressesController::class, 'get']);
        Route::get('delete', [AddressesController::class, 'delete']);
        Route::post('update', [AddressesController::class, 'update']);
    });

    Route::group(['prefix' => 'translate'] , function (){
        Route::get('list', [TranslateController::class, 'getAllTranslate']);
    });

    Route::group(['prefix' => 'category'] , function (){
        Route::get('list', [CategoryController::class, 'getCategory']);
    });

});
