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
            '*', 'App\Http\ViewComposers\ActivityComposer'
        );

        // make list of formats available to the format select drop down
        View::composer(
            'activity.partials._format', 'App\Http\ViewComposers\ActivityFormatComposer'
        );


        View::composer(
            ['activity.staff.design', 'activity.staff.dashboard'],
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
