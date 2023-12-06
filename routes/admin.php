<?php

use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\TransferController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserDistinctionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TranslateController;
use App\Http\Controllers\Admin\ComplaintsController;
use App\Http\Controllers\Admin\SanctionController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\EvaluationController;
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

    Route::get('/' , [HomeController::class , 'index'])->name('admin.dashboard');

    Route::get('users/{status}/{type}' , [UserController::class , 'index'])->name('usersByStatusType')
        ->where('status', 'all|pending|active|rejected|blocked')
        ->where('type', 'client|delivery|chef');

    Route::put('users/update/{id}/status' , [UserController::class , 'update_status'])->name('users.update.status') ;
    Route::resource('users', UserController::class)->names('users');
    //translations
    Route::resource('translations', TranslateController::class);
    //complaints
    Route::resource('complaints', ComplaintsController::class);
    //sanctions
    Route::resource('sanctions', SanctionController::class);
    //meals
    Route::get('meals/{status?}' , [MealController::class , 'index'])
        ->name('admin.meals.index')
        ->where('status','new|confirmed|disabled');
    Route::put('meals/update' , [MealController::class , 'update'])->name('admin.meals.update');
    Route::get('meals/{id}' , [MealController::class , 'show'])->name('admin.meals.show');

    //offers
    Route::get('offers/{type?}', [OfferController::class, 'index'])->name('admin.offer.index');

   //evaluation
    Route::get('evaluation', [EvaluationController::class, 'index'])->name('admin.evaluation.index');

    //ratings
    Route::get('ratings/{id?}', [RatingController::class, 'index'])->name('admin.rating.index');


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
        'prefix' => 'distinction',
        'controller' =>UserDistinctionController::class
    ], function () {

        Route::get('/{status?}','index')->name('admin.distinction.index')
        ->whereIn('status' ,['new' , 'active' ,'ended' ,'rejected']) ;
        Route::get('/{id}','show')->name('admin.distinction.show')->whereNumber('id') ;
        Route::post('/{id}','reject')->name('admin.distinction.Reject')->whereNumber('id') ;
        Route::post('/{id}/approved','approved')->name('admin.distinction.approved')->whereNumber('id') ;


    });



    Route::group([
        'prefix' => 'settings',
        'controller' =>ConfigurationController::class
    ], function () {
        Route::get('/{id}' ,'show')->name('admin.settings.configuration')->whereNumber('id');
    });

    Route::group([
        'prefix' => 'transactions',
        'controller' =>TransactionController::class
    ], function () {
        Route::get('/{status?}' ,'index')->name('admin.transactions.index')
            ->whereIn('status' ,['pending' , 'success' ,'failed' ,'completed' ,'uncompleted']) ;

        Route::get('/{id}' ,'show')->name('admin.transactions.show')->whereNumber('id');
    });



    Route::group([
        'prefix' => 'transfer',
        'controller' =>TransferController::class
    ], function () {
        Route::get('records/{type}' ,'records')->name('admin.transfer.records') ->whereIn('type' ,[/*'admin' ,*/ 'delivery' ,'chef' ]) ;
        Route::get('driver-cash' ,'driver_cash')->name('admin.driver-cash')  ;
        Route::get('driver-cash/{id}/user' ,'driver_cash_user')->name('admin.driver-cash-user')  ;

        Route::get('records/{id}/user' ,'records_user')->name('admin.transfer.records_user') ->whereNumber('id');
        Route::post('records/{id}/checked' ,'records_checked')->name('admin.transfer.records_checked') ->whereNumber('id');


    });

});

Route::get('t' , function (){
    return view('layouts.app');
});







