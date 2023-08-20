<?php
namespace Crudvel\Libraries\Helpers;

use \Crudvel\Libraries\Helpers\StaticResourceTrait;

trait ResourceTrait
{
// [End Specific Logic]
  public function cvActions($resource = null) {
    $actions = [];
    if($resource && ($controller = $this->cvControllers()[$this->cvCaseFixer('plural|slug',$resource)]??null)){
      $controllerActions = $this->getActions()[$this->cvCaseFixer('plural|slug',$resource)] ?? null;
      if (!$controllerActions){
        $newController = (new $controller);

        if(!method_exists($newController,'getActions'))
          return [];

        return $newController->getActions();
      }
      return $controllerActions;
    }

    if ($this->getCalculedActions())
      return $this->mergeActions();

    foreach ($this->cvControllers() as $controllerResource => $controllerClass)
      $actions[$controllerResource] = (new $controllerClass)->getActions();

    $this->setCalculedActions($actions);

    return $this->mergeActions();
  }

  public function cvControllers() {
    if ($this->getControllers())
      return $this->getControllers();

    $controllerFiles = scandir(app_path('Http/Controllers/Api'));
    $controllers = [];
    foreach ($controllerFiles as $controllerKey => $controller) {
      if($controller==='.' || $controller==='..')
        continue;

      $currentResource    = $this->cvCaseFixer('singular|studly',str_replace('Controller.php','',$controller));
      $currentController  = "App\Http\Controllers\Api\\{$this->cvStudlyCase($currentResource)}Controller";
      if(class_exists($currentController))
        $controllers[$this->cvCaseFixer('plural|slug',$currentResource)] = $currentController;
    }

    return $this->setControllers($controllers)->getControllers();
  }

  public function cvSeeds(String $subPath=''){
    if (is_callable($this,'getSeeds') && $this->getSeeds())
      return $this->getSeeds();
/*
    $seedFiles = $this->scanFilesOnDir(database_path('seeders'));
    $seeders   = [];
    foreach ($seedFiles as $seedKey => $seed) {
      if($seed===database_path('DatabaseSeeder.php'))
        continue;

      $seederPatter   = str_replace('.php','',str_replace(base_path(),'',$seed));
      $indexSeed      = str_replace('/','-',str_replace('database/seeders/','',$seederPatter));
      $classSeparator = '';
      $seederClass = array_reduce(explode('/',$seederPatter),function($sClass,$segment) use(&$classSeparator){
        if ($segment ==='')
          return $sClass;

        $fix = "{$sClass}{$classSeparator}{$this->cvUcfirstCase($segment)}";
        $classSeparator = '\\';
        return $fix;
      },'');

      if(class_exists($seederClass))
        $seeders[$this->cvSlugCase($indexSeed)] = $seederClass;
    }*/
    return $this->setSeeds(StaticResourceTrait::cvSeeds($subPath))->getSeeds();
  }

  public function cvResources() {
    return array_keys($this->cvControllers());
  }

  protected function addActions($controllerResource=null,$actions=null){
    if(!$controllerResource)
      return $this;
    $this->actions[$controllerResource]=$actions;
    return $this;
  }

  protected function mergeActions () {
    $actions = [];
    foreach($this->getCalculedActions() AS $controllerActions)
      foreach($controllerActions AS $action)
        if(empty($actions[$action]))
          $actions[$action] = true;
    return array_keys($actions);
  }

  public function cvResourcesCatalog(){
    $resources = [];

    foreach(cvResources() as $resource){
      $resources[]=[
        'label' => __("crudvel/".$resource.".row_label") ?? $resource,
        'value' => $resource,
      ];
    }

    return $resources;
  }

  public function scanFilesOnDir($source_dir){
    return StaticResourceTrait::staticScanFilesOnDir($source_dir);
    /*
    $files    = [];
    $segments = scandir($source_dir);
    $skipes = [
      '.' => true,
      '..' => true,
    ];

    foreach ($segments as $position => $segment) {
      if(($skipes[$segment]??false))
        continue;

      if (!is_dir("{$source_dir}".DIRECTORY_SEPARATOR."{$segment}")){
        $files[]="{$source_dir}".DIRECTORY_SEPARATOR."{$segment}";

        continue;
      }

      $files = array_merge($files,$this->scanFilesOnDir("{$source_dir}".DIRECTORY_SEPARATOR."{$segment}"));
    }

    return $files;*/
  }
// [Getters]
  protected function getControllers(){
    return $this->controllers;
  }

  protected function getSeeds(){
    return $this->seeders;
  }

  protected function getActions(){
    return $this->actions;
  }

  protected function getCalculedActions(){
    return $this->calculedActions??null;
  }
// [End Getters]
// [Setters]
  protected function setControllers($controllers){
    $this->controllers = $controllers;

    return $this;
  }

  protected function setSeeds($seeders){
    $this->seeders = $seeders;

    return $this;
  }

  protected function setActions($actions){
    $this->actions = $actions;
    return $this;
  }

  protected function setCalculedActions($actions = null){
    $this->calculedActions = $actions ?? null;

    return $this;
  }
// [End Setters]
}
