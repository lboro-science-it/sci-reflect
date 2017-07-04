<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            '*', 'App\Http\ViewComposers\ActivityComposer'
        );

        View::composer(
            'activity.student', 'App\Http\ViewComposers\StudentActivityComposer'
        );

        View::composer(
            'activity.partials._format', 'App\Http\ViewComposers\ActivityFormatComposer'
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
