<?php

use App\Http\Controllers\api\v1\AdditionCategoryController;
use App\Http\Controllers\api\v1\AdditionController;
use App\Http\Controllers\api\v1\AddressesController;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\CartController;
use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\ChefController;
use App\Http\Controllers\api\v1\ComplaintController;
use App\Http\Controllers\api\v1\ContentController;
use App\Http\Controllers\api\v1\ConversationController;
use App\Http\Controllers\api\v1\CountriesController;
use App\Http\Controllers\api\v1\DeliveryOderController;
use App\Http\Controllers\api\v1\MealController;
use App\Http\Controllers\api\v1\PusherController;
use App\Http\Controllers\api\v1\SendSMSController;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\SanctionController;
use App\Http\Controllers\api\v1\RatingController;
use App\Http\Controllers\api\v1\TranslateController;
use App\Http\Controllers\api\v1\UserLiveLocationController;
use App\Http\Controllers\api\v1\UserOrderController;
use App\Http\Controllers\api\v1\BankInfoController;
use App\Http\Controllers\api\v1\OfferController;
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

Route::get('pusher', [PusherController::class, 'test']);
Route::get('countries', [CountriesController::class, 'index'])->middleware('set_lang');
Route::get('translate', [TranslateController::class, 'getAllTranslate'])->middleware('set_lang');

Route::group(['prefix' => 'auth', 'middleware' => ['set_lang']], function () {
    Route::post('login', [AuthController::class, 'login']);//->middleware('guest:sanctum');
    Route::post('register', [AuthController::class, 'register']);//->middleware('guest:sanctum');
    Route::post('social-login', [AuthController::class, 'social_login']);//->middleware('guest:sanctum');
    Route::post('forget-password', [AuthController::class, 'forget_password']);//->middleware('guest:sanctum');
    Route::post('reset-password', [AuthController::class, 'reset_password']);//->middleware('guest:sanctum');
});

Route::group(['middleware' => ['auth:sanctum', 'set_lang']], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('send-sms', [SendSMSController::class, 'sendSms']);
        Route::post('update-profile', [AuthController::class, 'update_profile']);
        Route::post('can-delivery', [AuthController::class, 'can_delivery']);
        Route::post('change-password', [AuthController::class, 'change_password']);
        Route::post('mobile-verified', [AuthController::class, 'mobile_verified']);
        Route::post('online-status', [AuthController::class, 'online_status']);
        Route::get('term-and-condition', [ContentController::class, 'term_and_condition']);
        Route::post('upload-documents', [AuthController::class, 'upload_documents']);
    });
    Route::group(['prefix' => 'bank_info'] ,function (){
        Route::get('get' , [BankInfoController::class , 'get']);
        Route::post('create' , [BankInfoController::class , 'create']);
        Route::post('update' , [BankInfoController::class , 'update']);
        Route::post('delete' , [BankInfoController::class , 'delete']);
    });
    Route::group(['prefix' => 'addresses'], function () {
        Route::post('create', [AddressesController::class, 'store']);
        Route::get('list', [AddressesController::class, 'list']);
        Route::get('get', [AddressesController::class, 'get']);
        Route::post('delete', [AddressesController::class, 'delete']);
        Route::post('update', [AddressesController::class, 'update']);
    });


    Route::get('category/list', [CategoryController::class, 'getCategory']);
    Route::group(['prefix' => 'complaint'], function () {
        Route::get('list', [ComplaintController::class, 'getComplaint']);
        Route::post('create', [ComplaintController::class, 'createComplaint']);
    });
    Route::post('kitchen-images', [AuthController::class, 'kitchenImages']);
    Route::group(['prefix' => 'user'] ,function (){
        Route::get('meals/list' , [MealController::class , 'user_meals']);
        Route::get('meals/get' , [MealController::class , 'user_get_meal']);
        Route::get('chefs' , [UserController::class , 'all_chefs']);
        Route::get('chef' , [UserController::class , 'get_chef']);

        Route::group(['prefix' => 'location'] ,function (){
            Route::post('create_or_update' , [UserController::class , 'create_or_update']);
        });

        Route::group(['prefix' => 'sanction'] ,function (){
            Route::get('list' , [SanctionController::class , 'list']);
            Route::get('seen' , [SanctionController::class , 'seen_sanction']);
        });

        Route::group(['prefix' => 'rating'] ,function (){
            Route::get('list' , [RatingController::class , 'list']);
            Route::post('create' , [RatingController::class , 'store']);
            Route::get('seen' , [RatingController::class , 'get_rating']);
        });

        Route::group(['prefix' => 'cart'], function () {
            Route::post('create', [CartController::class, 'store']);
            Route::get('list', [CartController::class, 'list']);
            Route::post('delete_item', [CartController::class, 'delete_item']);
            Route::post('delete_all', [CartController::class, 'delete_all']);
            Route::post('update_quantity', [CartController::class, 'update_quantity']);
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::post('create', [UserOrderController::class, 'store']);
            Route::post('re_order', [UserOrderController::class, 're_order']);
            Route::get('get', [UserOrderController::class, 'get']);
            Route::get('list', [UserOrderController::class, 'list']);
            Route::post('cancel', [UserOrderController::class, 'cancel']);
        });


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

        Route::group(['prefix' => 'orders'], function () {
            Route::get('list', [ChefController::class, 'list']);
            Route::get('get', [ChefController::class, 'get']);
            Route::post('update_status', [ChefController::class, 'update_status']);
            Route::get('gat_delivery', [ChefController::class, 'gat_delivery']);
            Route::post('assign_delivery', [ChefController::class, 'assign_delivery']);
        });

        Route::group(['prefix' => 'offers'], function () {
            Route::post('create', [OfferController::class, 'store']);
            Route::get('list', [OfferController::class, 'list']);
            Route::post('delete', [OfferController::class, 'delete']);
            Route::post('update', [OfferController::class, 'update']);
        });
    });
    Route::group(['prefix' => 'delivery'], function () {
        Route::post('create_or_update_live_location' ,  UserLiveLocationController::class   );
        Route::group(['prefix' => 'orders'], function () {
            Route::get('requested', [DeliveryOderController::class, 'requested']);
            Route::post('update_request', [DeliveryOderController::class, 'update_request']);
            Route::post('update_status', [DeliveryOderController::class, 'update_status']);
            Route::get('list', [DeliveryOderController::class, 'list']);
            Route::get('get', [DeliveryOderController::class, 'get']);
        });
    });
    Route::group(['prefix' => 'conversations'], function () {
        Route::get( 'get'   ,     [ConversationController::class , 'get']);
        Route::get( 'list'   ,     [ConversationController::class , 'list']);
        Route::post('create' ,  [ConversationController::class , 'store']);
        Route::post('send_message' ,  [ConversationController::class , 'send_message']);
        Route::post('delete' ,  [ConversationController::class , 'delete']);
    });
    Route::group([
        'prefix' => 'notification',
        'controller' =>\App\Http\Controllers\api\v1\NotificationController::class,
    ], function () {

        Route::get( 'list'  ,'list');
        Route::post( 'seen'  ,'seen');
        Route::post( 'seen_all'  ,'seen_all');
        Route::post( 'delete_all'  ,'delete_all');

    });



});
