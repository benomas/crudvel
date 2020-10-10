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
      __DIR__.'/resources/lang/es'=>base_path('resources/lang/es'),
      __DIR__.'/resources/lang/en'=>base_path('resources/lang/en'),
      __DIR__.'/config'=>base_path('config/packages/benomas/crudvel'),
    ]);

    $this->publishes([__DIR__.'/customs/' => base_path('customs/crudvel/')], 'cv-customs');

    if ($this->app->runningInConsole()) {
      $commandClasses=[
        'Customs\Crudvel\Commands\CvRolePermissionLayout',
        'Customs\Crudvel\Commands\InstallCommand',
        'Customs\Crudvel\Commands\MakeRootUser',
        'Customs\Crudvel\Commands\CvScaff',
        'Customs\Crudvel\Commands\CvScaffFixTrees',
        'Customs\Crudvel\Commands\CvScaffBackCreateResource',
        'Customs\Crudvel\Commands\CvScaffBackDeleteResource',
        'Customs\Crudvel\Commands\CvScaffBackCreateCatResource',
        'Customs\Crudvel\Commands\CvScaffBackDeleteCatResource',
        'Customs\Crudvel\Commands\CvScaffFrontCreateResource',
        'Customs\Crudvel\Commands\CvScaffFrontDeleteResource',
        'Customs\Crudvel\Commands\CvScaffFrontCreateCatResource',
        'Customs\Crudvel\Commands\CvScaffFrontDeleteCatResource',
        'Customs\Crudvel\Commands\CvScaffCreateResource',
        'Customs\Crudvel\Commands\CvScaffDeleteResource',
        'Customs\Crudvel\Commands\CvScaffCreateCatResource',
        'Customs\Crudvel\Commands\CvScaffDeleteCatResource',
        'Customs\Crudvel\Commands\CvScaffList',
      ];

      foreach($commandClasses as $key=>$commandClass)
        if(!class_exists($commandClass))
          $commandClasses[$key] = str_replace('Customs\\','',$commandClass);

      $commandClasses[]='Crudvel\Commands\FixCustomExt';

      foreach($commandClasses as $key=>$commandClass)
        if(!class_exists($commandClass))
          unset($commandClasses[$key]);

      $this->commands($commandClasses);
    }
  }
  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
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
