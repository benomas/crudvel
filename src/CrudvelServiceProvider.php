<?php

namespace Benomas\Crudvel;

use Illuminate\Support\ServiceProvider;
//use Benomas\Crudvel\Commands\PermissionSystemMigrations;
use Benomas\Crudvel\Commands\ScaffoldingCommand;
use Illuminate\Support\Facades\Schema;


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
            __DIR__.'/customs/' => base_path('crudvel/customs'),
        ]);
        try{
            $migrationsPath = base_path("database/migrations");
            if(!file_exists($migrationsPath))
                mkdir($migrationsPath);
            $migrations =[];

            if(!Schema::hasTable("roles"))
                $migrations[]= "create_roles_table";
            if(!Schema::hasTable("role_users"))
                $migrations[]= "create_role_users_table";
            if(!Schema::hasTable("permissions"))
                $migrations[]= "create_permissions_table";
            //if(!Schema::hasTable("permission_role"))
                $migrations[]= "create_permission_role_table";
            foreach ($migrations  as $baseName)
                $this->loadMigrationsFrom(__DIR__.'/database/migrations/migrations/'.$baseName);
        }
        catch(\Exception $e){
            echo "Exception, the proccess fail.";
            return false;
        }
    
        echo "proccess completed";
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('make.scaffold', function () {
            return new ScaffoldingCommand();
        });
        $this->commands('make.scaffold');
    }
}
