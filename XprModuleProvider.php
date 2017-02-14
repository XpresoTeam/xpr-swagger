<?php

namespace XprSwagger;

use Illuminate\Support\ServiceProvider;

class XprModuleProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        /**
         * Check for old Routes file
         */
        require_once ('routes.php');

        $this->registerCommands();
    }

    public function registerCommands()
    {
        /**
         * Set command on App
         */
        $this->app->singleton('xpreso:swagger:routes', function ($app) {
            return $app['XprSwagger\\Console\\SwaggerRoutes'];
        });

        /**
         * Register commands
         */
        $this->commands('xpreso:swagger:routes');
    }
}