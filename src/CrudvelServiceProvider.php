<?php namespace Benomas\Crudvel;

use Illuminate\Support\ServiceProvider;


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
      __DIR__.'/resources/lang/es' =>
        base_path('resources/lang/es'),
      __DIR__.'/resources/lang/en' =>
        base_path('resources/lang/en'),
      __DIR__.'/customs/Controllers/ApiController.txt' =>
        base_path('customs/crudvel/Controllers/ApiController.php'),
      __DIR__.'/customs/Controllers/WebController.txt' =>
        base_path('customs/crudvel/Controllers/WebController.php'),
      __DIR__.'/customs/Controllers/WebController.txt' =>
        base_path('customs/crudvel/Controllers/WebController.php'),
      __DIR__.'/customs/Controllers/CatFileController.txt' =>
        base_path('customs/crudvel/Controllers/CatFileController.php'),
      __DIR__.'/customs/Controllers/FileController.txt' =>
        base_path('customs/crudvel/Controllers/FileController.php'),
      __DIR__.'/customs/Database/Migrations/BaseMigration.txt' =>
        base_path('customs/crudvel/Database/Migrations/BaseMigration.php'),
      __DIR__.'/customs/Database/Seeds/BaseSeeder.txt' =>
        base_path('customs/crudvel/Database/Seeds/BaseSeeder.php'),
      __DIR__.'/customs/Database/Seeds/CatPermissionTypeTableSeeder.txt' =>
        base_path('customs/crudvel/Database/Seeds/CatPermissionTypeTableSeeder.php'),
      __DIR__.'/customs/Models/BaseModel.txt' =>
        base_path('customs/crudvel/Models/BaseModel.php'),
      __DIR__.'/customs/Models/CatPermissionType.txt' =>
        base_path('customs/crudvel/Models/CatPermissionType.php'),
      __DIR__.'/customs/Models/Permission.txt' =>
        base_path('customs/crudvel/Models/Permission.php'),
      __DIR__.'/customs/Models/Role.txt' =>
        base_path('customs/crudvel/Models/Role.php'),
      __DIR__.'/customs/Models/User.txt'
        => base_path('customs/crudvel/Models/User.php'),
      __DIR__.'/customs/Models/CatFile.txt'
        => base_path('customs/crudvel/Models/CatFile.php'),
      __DIR__.'/customs/Models/File.txt'
        => base_path('customs/crudvel/Models/File.php'),
      __DIR__.'/customs/Requests/CrudRequest.txt' =>
        base_path('customs/crudvel/Requests/CrudRequest.php'),
      __DIR__.'/customs/Requests/CatFileRequest.txt' =>
        base_path('customs/crudvel/Requests/CatFileRequest.php'),
      __DIR__.'/customs/Requests/FileRequest.txt' =>
        base_path('customs/crudvel/Requests/FileRequest.php'),
      __DIR__.'/customs/Validations/CustomValidator.txt' =>
        base_path('customs/crudvel/Validations/CustomValidator.php'),
      __DIR__.'/customs/Commands/CvScaff.txt' =>
        base_path('customs/crudvel/Commands/CvScaff.php'),
      __DIR__.'/customs/Scaff/scaff_tree.json' =>
        base_path('customs/crudvel/Scaff/scaff_tree.json'),
      __DIR__.'/customs/Scaff/Classes/README.md' =>
      base_path('customs/crudvel/Scaff/Classes/README.md'),
      __DIR__.'/customs/Scaff/templates/README.md' =>
        base_path('customs/crudvel/Scaff/templates/README.md'),

      __DIR__.'/config' =>
        base_path('config/packages/benomas/crudvel'),
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
      return new \Crudvel\Commands\InstallCommand();
    });
    $this->commands('install:crudvel');

    $this->app->singleton('make:root-user', function () {
      return new \Crudvel\Commands\MakeRootUser();
    });
    $this->commands('make:root-user');

    $this->app->singleton('cvHelper', function () {
      return new  \Crudvel\Libraries\Helpers\CvHelper();
    });
    $this->app->singleton('cvCache', function () {
      return new \Crudvel\Libraries\CvCache();
    });

    $this->app->singleton('cvResource', function ($app) {
      return new \Crudvel\Libraries\CvResource\CvResource($app);
    });

    $this->app->booting(function() {
      $loader =\Illuminate\Foundation\AliasLoader::getInstance();
      $loader->alias('CvCache', \Crudvel\Facades\CvCache::class);
      $loader->alias('CvHelper', \Crudvel\Facades\CvHelper::class);
      $loader->alias('CvResource', \Crudvel\Facades\CvResource::class);
    });
  }
}
