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
        $this->app->bind('ChartHelper', function() {
            return new \App\Reflect\ChartHelper();
        });

        $this->app->singleton('SelectionsHelper', function($app) {
            return new \App\Reflect\SelectionsHelper($app->request);
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
