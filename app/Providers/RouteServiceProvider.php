<?php

namespace App\Providers;

use App\Activity;
use App\Round;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Load {activity} with pivot related to Auth::user, save pivot data
        // to Auth::user, and eager load Auth::user's selections for activity
        Route::bind('activity', function($value) {
            $activity = Activity::findOrFail($value);

            // make session data about user role / state available in model
            // session data set on launch / change of page / round
            $userActivities = $this->app->request->session()->get('activities');
            Auth::user()->role = $userActivities[$value]['role'];
            Auth::user()->currentRound = $userActivities[$value]['currentRound'];
            Auth::user()->currentPage = $userActivities[$value]['currentRound'];

            return $activity;
        });

        // Load $round model based on {round_number} in {activity}
        // this also loads all of the activity's rounds...
        // todo: consider whether loading all rounds is desireable in all cases.
        Route::bind('round', function($value) {
            $activity = $this->app->request->route('activity');
            $round = $activity->rounds->where('round_number', $value)
                                      ->first();

            return $round;
        });

        // Load $page model based on number in {round}
        Route::bind('roundPage', function($value) {
            $round = $this->app->request->route('round');
            $page = $round->pages->where('pivot.page_number', $value)->first();

            return $page;
        });

        Route::bind('category', function($value) {
            $activity = $this->app->request->route('activity');
            $category = $activity->categories->where('slug', $value)->first();

            return $category;
        });

    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
