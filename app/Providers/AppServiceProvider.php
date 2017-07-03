<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('RatingsHelper', function() {
            return new \App\Reflect\RatingsHelper();
        });

        $this->app->singleton('SelectionHelper', function($app) {
            return new \App\Reflect\SelectionHelper($app->request);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Reflect', function($app) {
            return new \App\Reflect\Reflect($app->request);
        });
    }
}
