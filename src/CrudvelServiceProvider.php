<?php

namespace Benomas\Crudvel;

use Illuminate\Support\ServiceProvider;
use Benomas\Crudvel\Commands\InstallCommand;
use Benomas\Crudvel\Commands\ScaffoldingCommand;
use Benomas\Crudvel\Commands\MakeRootUser;

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
            __DIR__.'/customs/Controllers/ApiController.txt' => base_path('crudvel/customs/Controllers/ApiController.php'),
            __DIR__.'/customs/Controllers/WebController.txt' => base_path('crudvel/customs/Controllers/WebController.php'),
            __DIR__.'/customs/database/migrations/BaseMigration.txt' => base_path('crudvel/customs/Database/Migrations/BaseMigration.php'),
            __DIR__.'/customs/Models/BaseModel.txt' => base_path('crudvel/customs/Models/BaseModel.php'),
            __DIR__.'/customs/Models/Permission.txt' => base_path('crudvel/customs/Models/Permission.php'),
            __DIR__.'/customs/Models/Role.txt' => base_path('crudvel/customs/Models/Role.php'),
            __DIR__.'/customs/Models/User.txt' => base_path('crudvel/customs/Models/User.php'),
            __DIR__.'/customs/Requests/CrudRequest.txt' => base_path('crudvel/customs/Requests/CrudRequest.php'),
            __DIR__.'/customs/Validations/CustomValidator.txt' => base_path('crudvel/customs/Validations/CustomValidator.php'),

            __DIR__.'/config' => base_path('config/packages/benomas/crudvel'),
        ]);
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('install:crudvel', function () {
            return new InstallCommand();
        });
        $this->commands('install:crudvel');
        
        $this->app->singleton('make:scaffold', function () {
            return new ScaffoldingCommand();
        });
        $this->commands('make:scaffold');
        
        $this->app->singleton('make:root-user', function () {
            return new MakeRootUser();
        });
        $this->commands('make:root-user');
    }
}
