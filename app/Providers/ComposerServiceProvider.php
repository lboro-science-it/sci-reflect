<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the view composers
     *
     * @return void
     */
    public function boot()
    {
        // make $activity available to all views
        View::composer(
            '*', 'App\ViewComposers\ActivityComposer'
        );

        View::composer(
            'linear.dashboard', 'App\ViewComposers\StudentDashboardComposer'
        );

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
