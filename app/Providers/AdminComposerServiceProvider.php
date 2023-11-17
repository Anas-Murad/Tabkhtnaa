<?php

namespace App\Providers;

use App\Models\Country;
use App\Models\Order;
use App\Models\Question;
use Cache;
use Illuminate\Support\ServiceProvider;
use View;

class AdminComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $countries = Cache::rememberForever('countries', function () {
            return  Country::with('cities')->get() ;
        });


        $SB_TOTAL_ORDERS =Order::count();
        $SB_PENDING_ORDERS =Order::whereStatus('pending')->count();
        $SB_CONFIRMED_ORDERS =Order::whereStatus('confirmed')->count();
        $SB_PREPARE_ORDERS =Order::whereStatus('prepare')->count();
        $SB_PREPARED_ORDERS =Order::whereStatus('prepared')->count();
        $SB_ON_WAY_ORDERS =Order::whereStatus('on_way')->count();
        $SB_DELIVERED_ORDERS =Order::whereStatus('delivered')->count();
        $SB_NOT_DELIVERED_ORDERS =Order::whereStatus('not_delivered')->count();
        $SB_REJECTED_ORDERS =Order::whereStatus('rejected')->count();
        $SB_CANCEL_ORDERS =Order::whereStatus('cancel')->count();
        $SB_NOT_ORDERED_ORDERS =Order::whereStatus('not_ordered')->count();

        View::composer('admin.*', function ($view) use (
            $countries ,
            $SB_TOTAL_ORDERS ,
            $SB_PENDING_ORDERS ,
            $SB_CONFIRMED_ORDERS ,
            $SB_PREPARE_ORDERS ,
            $SB_PREPARED_ORDERS ,
            $SB_ON_WAY_ORDERS ,
            $SB_DELIVERED_ORDERS ,
            $SB_NOT_DELIVERED_ORDERS ,
            $SB_REJECTED_ORDERS ,
            $SB_CANCEL_ORDERS ,
            $SB_NOT_ORDERED_ORDERS
        ){
            $view->with('countries',$countries);
            $view->with('SB_TOTAL_ORDERS',$SB_TOTAL_ORDERS);
            $view->with('SB_PENDING_ORDERS',$SB_PENDING_ORDERS);
            $view->with('SB_CONFIRMED_ORDERS',$SB_CONFIRMED_ORDERS);
            $view->with('SB_PREPARE_ORDERS',$SB_PREPARE_ORDERS);
            $view->with('SB_PREPARED_ORDERS',$SB_PREPARED_ORDERS);
            $view->with('SB_ON_WAY_ORDERS',$SB_ON_WAY_ORDERS);
            $view->with('SB_DELIVERED_ORDERS',$SB_DELIVERED_ORDERS);
            $view->with('SB_NOT_DELIVERED_ORDERS',$SB_NOT_DELIVERED_ORDERS);
            $view->with('SB_REJECTED_ORDERS',$SB_REJECTED_ORDERS);
            $view->with('SB_CANCEL_ORDERS',$SB_CANCEL_ORDERS);
            $view->with('SB_NOT_ORDERED_ORDERS',$SB_NOT_ORDERED_ORDERS);

        });

    }
}
