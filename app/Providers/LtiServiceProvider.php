<?php

namespace App\Providers;

use App\Lti\LtiToolProvider;
use DB;
use Illuminate\Support\ServiceProvider;
use IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector;

class LtiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Registers the LtiToolProvider interface to the IMSGlobal library.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LtiToolProvider::class, function($app) {
            $db = DB::connection()->getPdo();
            $dbConnector = DataConnector::getDataConnector('', $db);
            return new LtiToolProvider($dbConnector);
        });
    }
}
