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
        $this->publishes([
            __DIR__.'/resources/lang/es' => base_path('resources/lang/es'),
            __DIR__.'/resources/lang/en' => base_path('resources/lang/en'),
        ]);
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
