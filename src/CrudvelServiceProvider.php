<?php

namespace Benomas\Crudvel;

use Illuminate\Support\ServiceProvider;
use Benomas\Crudvel\Commands\PermissionSystemMigrations;
use Benomas\Crudvel\Commands\ScaffoldingCommand;


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
        $this->app->singleton('make.permission', function () {
            return new PermissionSystemMigrations();
        });
        $this->app->singleton('make.scaffold', function () {
            return new ScaffoldingCommand();
        });
        $this->commands('make.permission');
        $this->commands('make.scaffold');
    }
}
