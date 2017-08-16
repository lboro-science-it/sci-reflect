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
            ['activity.staff.design', 'activity.staff.dashboard'],
            'App\ViewComposers\StaffActivityComposer'
        );

        View::composer(
            'activity.staff.partials._tasks',
            'App\ViewComposers\StaffTasksComposer'
        );

        View::composer(
            'activity.student', 'App\ViewComposers\StudentActivityComposer'
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
