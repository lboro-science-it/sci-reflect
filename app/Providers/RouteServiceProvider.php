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
            $activity = Auth::user()->activities->where('pivot.activity_id', $value)->first();

            // $activity->rounds are required globally
            $activity->load([
                'rounds'
            ]);

            // store pivot data for Auth::user's relationship to $activity
            Auth::user()->role = $activity->pivot->role;
            Auth::user()->currentRound = $activity->pivot->current_round;
            Auth::user()->currentPage = $activity->pivot->current_page;

            // global eager load Auth::user's selections
            Auth::user()->load([
                'selections' => function($q) use ($activity) {
                    $roundIds = array_column($activity->rounds->toArray(), 'id');
                    $q->whereIn('round_id', $roundIds);
                }
            ]);

            return $activity;
        });

        // Load {round} based on number of {round} in {activity}
        Route::bind('round', function($value) {
            $activity = $this->app->request->route('activity');
            $round = $activity->rounds->where('round_number', '=', $value)->first();

            // globally eager load round's pages & data - used in pages + charts
            $round->load([
                'pages.skills.indicators'
            ]);
            return $round;
        });

        // Load {page} based on number of {page} in {round}.
        // Also load all round's pages' indicators, required for table of
        // contents & navigation buttons, etc
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
