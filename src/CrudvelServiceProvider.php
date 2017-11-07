<?php

namespace Benomas\Crudvel;

use Illuminate\Support\ServiceProvider;
use Benomas\Crudvel\Commands\PermissionSystemMigrations;

class CrudvelServiceProvider extends ServiceProvider
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
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['make.permission'] = $this->app->share(function($app)
        {
            return new PermissionSystemMigrations();
        });
        $this->commands('make.permission');
    }
}
