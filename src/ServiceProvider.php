<?php

namespace Marshmallow\GoogleAnalytics;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Merge in the config
         */
        $this->mergeConfigFrom(
            __DIR__ . '/../config/google-analytics.php',
            'google-analytics'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Config
         */
        $this->publishes([
            __DIR__ . '/../config/google-analytics.php' => config_path('google-analytics.php'),
        ]);
    }
}
