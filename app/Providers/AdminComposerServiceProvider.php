<?php

namespace App\Providers;

use App\Models\Country;
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
        View::composer('admin.*', function ($view) use ($countries){
            $view->with('countries',$countries);
        });

    }
}
