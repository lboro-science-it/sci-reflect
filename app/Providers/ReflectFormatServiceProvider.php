<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ReflectFormatServiceProvider extends ServiceProvider
{
    /**
     * Binds each Format Class from App\Reflect\Reflect.php's $formats
     * into the service container.
     *
     * @return void
     */
    public function boot()
    {
        $formats = app('Reflect')->getFormatClassNames();

        foreach($formats as $format) {
            $className = '\App\Reflect\Formats\\' . $format;
            $this->app->bind($format, function($app) use ($className) {
                return new $className($app->request);
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
