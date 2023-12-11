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

        Route::get('/{status?}/','index')->name('admin.distinction.index')
        ->whereIn('status' ,['new' , 'active' ,'ended' ,'rejected']) ;
        Route::get('/{id}','show')->name('admin.distinction.show')->whereNumber('id') ;
        Route::post('/{id}','reject')->name('admin.distinction.Reject')->whereNumber('id') ;
        Route::post('/{id}/approved','approved')->name('admin.distinction.approved')->whereNumber('id') ;


    });

    Route::group([
        'prefix' => 'settings/countries',
        'controller' =>\App\Http\Controllers\Admin\CountryController::class
    ], function () {
        Route::get('/' ,'index')->name('admin.settings.countries');
        Route::get('/create' ,'create')->name('admin.settings.countries.create');
        Route::get('/{id}/edit' ,'create')->name('admin.settings.countries.edit');
        Route::put('/{id}/update' ,'create')->name('admin.settings.countries.update');
    });

    Route::group([
        'prefix' => 'settings/cities',
        'controller' =>\App\Http\Controllers\Admin\CityController::class
    ], function () {


        Route::get('/{country_id}/' ,'index')->name('admin.settings.countries.cities');
        Route::get('/{country_id}/edit/{city_id}' ,'edit')->name('admin.settings.cities.edit');
        Route::delete('/{country_id}/destroy/{city_id}' ,'destroy')->name('admin.settings.cities.destroy');
        Route::put('/{country_id}/update/{city_id}' ,'update')->name('admin.settings.cities.update');
        Route::post('/{country_id}/store' ,'store')->name('admin.settings.cities.store');

        Route::get('/{country_id}/cities/create' ,'create')->name('admin.settings.cities.create');



        //        Route::get('/{id}/cities/{city_id}/edit' ,'create')->name('admin.settings.countries.create');
//        Route::get('/{id}/cities/{city_id}/update' ,'create')->name('admin.settings.countries.create');
//        Route::get('/{id}/cities/{city_id}/delete' ,'create')->name('admin.settings.countries.create');


    });



    Route::group([
        'prefix' => 'settings',
        'controller' =>\App\Http\Controllers\Admin\ConfigurationController::class
    ], function () {
        Route::get('/{classification}' ,'choose_country')->name('admin.settings.choose_country');
        Route::get('/{classification}/edit' ,'edit')->name('admin.settings.edit');
        Route::post('/{classification}/{country}/save' ,'update')->name('admin.settings.update');
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
        Route::get('/{type}' ,'transfer')->name('admin.transfer.index')
            ->whereIn('type' ,[/*'admin' ,*/ 'delivery' ,'chef' ]) ;
        Route::get('/{transfer_id}/records' ,'transfer_records')->name('admin.transfer.transfer_records') ->whereNumber('transfer_id') ;




        Route::get('records/{type}/{status?}' ,'records')->name('admin.transfer.records') ->whereIn('type' ,[/*'admin' ,*/ 'delivery' ,'chef' ]) ;



        Route::post('records/{id}/checked' ,'records_checked')->name('admin.transfer.records_checked') ->whereNumber('id');


        Route::get('driver-cash' ,'driver_cash')->name('admin.driver-cash')  ;
        Route::get('driver-cash/{id}/user' ,'driver_cash_user')->name('admin.driver-cash-user')  ;
        Route::get('records/{id}/user/' ,'records_user')->name('admin.transfer.records_user') ->whereNumber('id');
        Route::get('records/{id}/user/transfer_screen' ,'transfer_screen')->name('admin.transfer.transfer_screen') ->whereNumber('id');
        Route::post('records/{id}/user/do_transfer' ,'do_transfer')->name('admin.transfer.do_transfer') ->whereNumber('id');




//        Route::get('checked/{type}/list' ,'records_checked_list')->name('admin.transfer.records.records_checked_list') ->whereIn('type' ,['delivery' ,'chef' ]) ;

    });

});

Route::get('t' , function (){
    return view('layouts.app');
});







