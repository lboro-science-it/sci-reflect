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
        View::composer(
            '*', 'App\Http\ViewComposers\ActivityComposer'
        );

        View::composer(
            'activity.partials._format', 'App\Http\ViewComposers\ActivityFormatComposer'
        );

        View::composer(
            ['activity.design', 'activity.staff'],
            'App\Http\ViewComposers\StaffActivityComposer'
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
