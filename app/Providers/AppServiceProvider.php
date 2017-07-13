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
        $this->app->singleton('SelectionsHelper', function($app) {
            return new \App\Reflect\SelectionsHelper($app->request);
        });

        $this->app->bind('ChartHelper', function($app) {
            return new \App\Reflect\ChartHelper($app->request);
        });

        $this->app->bind('SkillsHelper', function($app) {
            return new \App\Reflect\SkillsHelper($app->make('Reflect'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Reflect', function($app) {
            return new \App\Reflect\Reflect($app->request);
        });
    }
}
