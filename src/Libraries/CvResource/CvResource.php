<?php

namespace Crudvel\Libraries\CvResource;
use Illuminate\Support\Str;
class CvResource
{
  use \Crudvel\Traits\CvResourceTrait;
  protected $camelPluralName;
  protected $camelSingularName;
  protected $slugPluralName;
  protected $slugSingularName;
  protected $snakePluralName;
  protected $snakeSingularName;
  protected $studlyPluralName;
  protected $studlySingularName;

  protected $controllerClass;
  protected $controllerInstance;
  protected $modelClass;
  protected $modelBuilderInstance;
  protected $modelCollectionInstance;
  protected $requestClass;
  protected $requestInstance;
  protected $userModelClass='App\Models\User';
  protected $userModelBuilderInstance;
  protected $userModelCollectionInstance;

  public function __construct(){
  }
  public function boot($controllerInstance=null){
    if(!$controllerInstance)
      return;
    $this->setControllerInstance($controllerInstance);
    $this->fixCases();
    $this->loadModel();
    $this->loadRequest();
    return $this;
  }

  public function startModelBuilderInstance(){
    if($this->modelClass)
      return $this->modelBuilderInstance = new $this->modelClass;
    return $this;
  }

  public function fixCases(){
    if($this->getControllerInstance()){
      $this->setSlugSingularName($this->fixedSlugSingularName());
      $this->setSlugPluralName($this->fixedSlugPluralName());
      $this->setSnakeSingularName($this->fixedSnakeSingularName());
      $this->setSnakePluralName($this->fixedSnakePluralName());
      $this->setCamelSingularName($this->fixedCamelSingularName());
      $this->setCamelPluralName($this->fixedCamelPluralName());
      $this->setStudlySingularName($this->fixedStudlySingularName());
      $this->setStudlyPluralName($this->fixedStudlyPluralName());
    }
  }

  public function fixedSlugSingularName($name = null){
    if($name)
      return Str::snake($name,'-');
    return $this->getControllerInstance()->getSlugSingularName();
  }

  public function fixedSlugPluralName($name = null){
    if($name)
      return Str::plural(Str::snake($name,'-'));
    return Str::plural($this->getControllerInstance()->getSlugSingularName());
  }

  public function fixedCamelSingularName($name = null){
    if($name)
      return Str::camel($name);
    return Str::camel($this->getControllerInstance()->getSlugSingularName());
  }

  public function fixedCamelPluralName($name = null){
    if($name)
      return Str::plural(Str::camel($name));
    return Str::plural(Str::camel($this->getControllerInstance()->getSlugSingularName()));
  }

  public function fixedSnakeSingularName($name = null){
    if($name)
      return Str::snake($name);
    return Str::snake($this->getControllerInstance()->getSlugSingularName());
  }

  public function fixedSnakePluralName($name = null){
    if($name)
      return Str::plural(Str::snake($name));
    return Str::plural(Str::snake($this->getControllerInstance()->getSlugSingularName()));
  }

  public function fixedStudlySingularName($name = null){
    if($name)
      return Str::studly($name);
    return Str::studly($this->getControllerInstance()->getSlugSingularName());
  }

  public function fixedStudlyPluralName($name = null){
    if($name)
      return Str::plural(Str::studly($name));
    return Str::plural(Str::studly($this->getControllerInstance()->getSlugSingularName()));
  }
  public function loadControlle($controller=null){
    if(!is_object($controller))
      return $this;
    return $this->setControlleClass(get_class($controller))->setControlleBuilderInstance($controller);
    if(!$controller || !class_exists($controller))
      return $this->generateControlle();
    return $this->setControlleClass($controller)->setControlleBuilderInstance($controller::noFilters());
  }
  public function loadModel($model=null){
    if(is_object($model))
      return $this->setModelClass(get_class($model))->setModelBuilderInstance($model);
    if(!$model || !class_exists($model))
      return $this->generateModel();
    return $this->setModelClass($model)->setModelBuilderInstance($model::noFilters());
  }
  public function loadRequest($request=null){
    if(is_object($request))
      return $this->setRequestClass(get_class($request))->setRequestInstance($request);
    if(!$request || !class_exists($request))
      return $this->generateRequest();
    return $this->setRequestClass($request)->captureRequest();
  }
  public function generateModel(){
    if(!($controller = $this->getControllerInstance()))
      return $this;
    if(($modelClassName = $controller->getModelClassName()) && class_exists($modelClassName)){
      $this->setModelClass($modelClassName);
    }else{
      if(!($studlySingularName = $this->getStudlySingularName()) || !class_exists('App\Models\\'.$studlySingularName))
        return $this;
      $modelClassName = 'App\Models\\'.$studlySingularName;
      $this->setModelClass($modelClassName);
    }
    $this->setModelBuilderInstance($modelClassName::noFilters());
    return $this;
  }
  public function generateRequest(){
    if(!($controller = $this->getControllerInstance()))
      return $this;
    if(($requestClassName = $controller->getRequestClassName()) && class_exists($requestClassName)){
      $this->setRequestClass($requestClassName);
    }else{
      if(!($studlySingularName = $this->getStudlySingularName()) || !class_exists('App\Http\Requests\\'.$studlySingularName.'Request'))
        return $this;
      $requestClassName = 'App\Http\Requests\\'.$studlySingularName.'Request';
      $this->setRequestClass($requestClassName);
    }
    return $this->setRequestClass($requestClassName)->captureRequest();
  }
  public function captureRequest(){
    $requestInstance = app($this->getRequestClass());
    if(!$this->getRequestInstance())
      $this->setRequestInstance($requestInstance);
    return $this;
  }
  public function captureRequestHack($requestInstance){
    if(!$this->getRequestInstance())
      $this->setRequestInstance($requestInstance);
    return $this;
  }
  public function langCase(){
    return $this->getSlugPluralName();
  }
  public function assignUser(){
    if(!($user = $this->getRequestInstance()->user()))
      return $this;
    $this->setUserModelBuilderInstance($this->getUserModelClass()::id($user->id));
    $this->setUserModelCollectionInstance($this->getUserModelBuilderInstance()->first());
  }
}