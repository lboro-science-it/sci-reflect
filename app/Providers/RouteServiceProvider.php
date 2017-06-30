<?php

namespace App\Providers;

use App\Activity;
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

        // bind the activity_user pivot to both $activity and Auth::user()
        // therefore by accessing Auth::user(), current_round, current_page, and role are available
        Route::bind('activity', function($value) {
            $activity = Auth::user()->activities()->where('activity_id', '=', $value)->first();
            Auth::user()->setRelation('pivot', $activity->pivot);

            return $activity;
        });

        // bind round based on round number in activity
        Route::bind('round', function($value) {
            $activity = $this->app->request->route('activity');
            $round = $activity->rounds()->where('round_number', '=', $value)->with('pages')->first();
            return $round;
        });

        // bind page based on page number in round
        // todo: if in category format, may need to rethink this.
        // potentially conditional based on presence of category in route.
        Route::bind('page', function($value) {
            $round = $this->app->request->route('round');
            $page = $round->pages->where('pivot.page_number', $value)->first();
            return $page;
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
