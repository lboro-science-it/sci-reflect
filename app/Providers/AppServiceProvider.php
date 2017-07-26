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
            return new \App\Reflect\ChartHelper($app->request, $app->make('Reflect'));
        });

        $this->app->bind('RatingsHelper', function($app) {
            return new \App\Reflect\RatingsHelper($app->request, $app->make('Reflect'));
        });

        $this->app->bind('SkillsHelper', function($app) {
            return new \App\Reflect\SkillsHelper($app->request, $app->make('Reflect'));
        });

        $this->app->bind('LinearActivity', function($app) {
            return new \App\Reflect\Formats\LinearFormat\LinearActivity($app->request);
        });
        $this->app->bind('LinearPage', function($app) {
            return new \App\Reflect\Formats\LinearFormat\LinearPage($app->request, $app->make('Reflect'));
        });

        $this->app->bind('NonLinearActivity', function($app) {
            return new \App\Reflect\Formats\NonLinearFormat\NonLinearActivity($app->request);
        });
        $this->app->bind('NonLinearPage', function($app) {
            return new \App\Reflect\Formats\NonLinearFormat\NonLinearPage($app->request, $app->make('Reflect'));
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
