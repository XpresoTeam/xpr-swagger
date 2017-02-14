<?php

namespace XprSwagger;

use XCore\XprModuleProvider as ServiceProvider;

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
        $this->app->singleton('xcoredev:swagger:routes', function ($app) {
            return $app['XCoreDev\\Console\\SwaggerRoutes'];
        });

        /**
         * Register commands
         */
        $this->commands('xcoredev:swagger:routes');
    }
}