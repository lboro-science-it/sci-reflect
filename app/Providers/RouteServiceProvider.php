<?php

namespace App\Providers;

use App\Activity;
use Auth;
use Debugbar;
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
            // todo: tidy up below, probably move to various eager load middlewares

            // only attempt to load via Auth::user pivot if user is authed
            // if not, they will be 'ejected' by the middleware anyway
            if (Auth::check()) {
                $activity = Auth::user()->activities->where('pivot.activity_id', $value)->first();
            }

            // if we successfully got the activity it means the user can access
            // the activity, so we load some stuff that is required in all requests
            if (isset($activity)) {
                // globally eager load $activity's rounds
                $activity->load([
                    'rounds'
                ]);

                // store pivot data for Auth::user's relationship to $activity
                Auth::user()->role = $activity->pivot->role;
                Auth::user()->currentRound = $activity->pivot->current_round;
                Auth::user()->currentPage = $activity->pivot->current_page;

                // globally eager load Auth::user's selections
                // todo: move this elsewhere as we don't need for Chart view necessarily
                if (Auth::user()->role == 'student') {
                    Auth::user()->load([
                        'selections' => function($q) use ($activity) {
                            $roundIds = array_column($activity->rounds->toArray(), 'id');
                            $q->whereIn('round_id', $roundIds);
                        }
                    ]);
                }
            } else {
                $activity = Activity::where('id', $value)->first();
            }

            return $activity;
        });

        // Load $round model based on {round_number} in {activity}
        Route::bind('round', function($value) {
            $activity = $this->app->request->route('activity');
            $round = $activity->rounds->where('round_number', $value)->first();

            return $round;
        });

        // Load $page model based on number in {round}
        Route::bind('page', function($value) {
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
