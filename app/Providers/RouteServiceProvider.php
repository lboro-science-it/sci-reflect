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

        // For any route with {activity}, we want to get the Authed user's
        // relationship, role, etc to it,
        // This means current_round, current_page, role are available.
        Route::bind('activity', function($value) {
            $activity = Auth::user()->activities()->where('activity_id', '=', $value)->first();

            Auth::user()->role = $activity->pivot->role;
            Auth::user()->currentRound = $activity->pivot->current_round;
            Auth::user()->currentPage = $activity->pivot->current_page;

            return $activity;
        });

        // {round} in a route refers to a round number in {activity} so we
        // use route('activity') to get the correct round model, this also
        // loads all rounds on the route('activity') model
        Route::bind('round', function($value) {
            $activity = $this->app->request->route('activity');
            $round = $activity->rounds->where('round_number', '=', $value)->first();
            return $round;
        });

        // {page} in a route refers to a page number in a {round} so we use
        // route('round') to get it - we also load round's pages which is
        // helpful for drawing tables of contents, etc
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
