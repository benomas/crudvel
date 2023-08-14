<?php
namespace Crudvel\Libraries\Helpers;

use Illuminate\Support\Str;

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

  public function cvSeeds() {
    if (is_callable($this,'getSeeds') && $this->getSeeds())
      return $this->getSeeds();

    return [];
    $seedFiles = scandir(database_path('seeders'));
    $seeders     = [];
    $testSeeds = [];

    foreach ($seedFiles as $seedKey => $seed) {
      if($seed==='.' || $seed==='..' || $seed==='DatabaseSeeder.php')
        continue;

      $currentResource = $this->cvCaseFixer('singular|studly',str_replace('TableSeeder.php','',$seed));
      $currentSeed     = "Database\Seeders\\{$this->cvStudlyCase($currentResource)}TableSeeder";

      if(class_exists($currentSeed))
        $seeders[$this->cvCaseFixer('plural|slug',$currentResource)] = $currentSeed;
    }

    return $this->setSeeds($seeders)->getSeeds();
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
