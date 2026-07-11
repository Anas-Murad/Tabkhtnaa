<?php

use App\Http\Controllers\Admin\BusinessSettingsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TranslateController;
use App\Http\Controllers\Admin\ComplaintsController;
use App\Http\Controllers\Admin\SanctionController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\AuditTrailController;
use App\Http\Controllers\Admin\LoyaltySettingController;
use App\Http\Controllers\Admin\LoyaltyTierController;
use App\Http\Controllers\Admin\LoyaltyTransactionController;
use App\Http\Controllers\Admin\LoyaltyDashboardController;
use App\Http\Controllers\Admin\LoyaltyCustomerController;
use App\Http\Controllers\Admin\DoublePointsCampaignController;
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
    //profile
    Route::get('profile' , [ProfileController::class , 'edit'])->name('admin.profile.edit');
    Route::put('profile/update' , [ProfileController::class , 'update'])->name('admin.profile.update');

    Route::get('settings', [BusinessSettingsController::class, 'edit'])->name('admin.settings.edit');
    Route::put('settings', [BusinessSettingsController::class, 'update'])->name('admin.settings.update');

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

    Route::get('audit-trails', [AuditTrailController::class, 'index'])->name('admin.audit_trails.index');
    Route::get('audit-trails/{id}', [AuditTrailController::class, 'show'])->name('admin.audit_trails.show')->whereNumber('id');

    Route::prefix('loyalty')->name('admin.loyalty.')->group(function () {
        Route::get('dashboard', [LoyaltyDashboardController::class, 'index'])->name('dashboard');
        Route::get('settings', [LoyaltySettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [LoyaltySettingController::class, 'update'])->name('settings.update');
        Route::get('tiers', [LoyaltyTierController::class, 'index'])->name('tiers.index');
        Route::post('tiers', [LoyaltyTierController::class, 'store'])->name('tiers.store');
        Route::post('tiers/update', [LoyaltyTierController::class, 'update'])->name('tiers.update');
        Route::delete('tiers/{id}', [LoyaltyTierController::class, 'destroy'])->name('tiers.destroy')->whereNumber('id');
        Route::get('transactions', [LoyaltyTransactionController::class, 'index'])->name('transactions.index');
        Route::get('campaigns', [DoublePointsCampaignController::class, 'index'])->name('campaigns.index');
        Route::post('campaigns', [DoublePointsCampaignController::class, 'store'])->name('campaigns.store');
        Route::put('campaigns/{id}', [DoublePointsCampaignController::class, 'update'])->name('campaigns.update')->whereNumber('id');
        Route::delete('campaigns/{id}', [DoublePointsCampaignController::class, 'destroy'])->name('campaigns.destroy')->whereNumber('id');
        Route::post('customers/{id}/add-points', [LoyaltyCustomerController::class, 'addPoints'])->name('customers.add-points')->whereNumber('id');
        Route::post('customers/{id}/deduct-points', [LoyaltyCustomerController::class, 'deductPoints'])->name('customers.deduct-points')->whereNumber('id');
        Route::post('customers/{id}/check-tier', [LoyaltyCustomerController::class, 'checkTier'])->name('customers.check-tier')->whereNumber('id');
    });
});

Route::get('t' , function (){
    return view('layouts.app');
});







