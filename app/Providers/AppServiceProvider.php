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

        $this->app->bind('SkillsHelper', function($app) {
            return new \App\Reflect\SkillsHelper($app->make('Reflect'));
        });

        $this->app->bind('LinearActivity', function($app) {
            return new \App\Reflect\Formats\LinearFormat\Activity($app->request);
        });
        $this->app->bind('LinearPage', function($app) {
            return new \App\Reflect\Formats\LinearFormat\Page($app->request, $app->make('Reflect'));
        });

        $this->app->bind('NonLinearActivity', function($app) {
            return new \App\Reflect\Formats\NonLinearFormat\Activity($app->request);
        });
        $this->app->bind('NonLinearPage', function($app) {
            return new \App\Reflect\Formats\NonLinearFormat\Page($app->request, $app->make('Reflect'));
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
