<?php

namespace App\Providers;

use App\Models\Channel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //View::share('channels', Channel::all());
        \View::composer('*', function ($view) {
            $view->with('channels', Channel::all());
        });
    }
}