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

        // compose dashboard with students, staff, groups, rounds
        View::composer(
            'activity.staff.dashboard',
            'App\ViewComposers\StaffActivityComposer'
        );

        // compose tasks partial with task status vars
        View::composer(
            'partials.staff.tasks',
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
