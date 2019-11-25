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
  protected $permissionModelClass='App\Models\Permission';
  protected $paginatorClass;
  protected $paginatorInstance;
  protected $paginatorDefiner;
  protected $rootInstance;

  protected $rows;
  protected $row;
  protected $currentAction;
  protected $currentActionKey;
  protected $actionResource;
  protected $actionsAccess;
  protected $fields;

  public function __construct(){
  }
  public function boot($controllerInstance=null){
    if(!$controllerInstance)
      return;
    $this->setControllerInstance($controllerInstance)->assignUser();
    $this->fixCases()->loadModel()->loadRequest()->loadPaginator();
    return $this;
  }

  public function startModelBuilderInstance(){
    if($this->modelClass)
      return $this->modelBuilderInstance = new $this->modelClass;
    return $this;
  }

  public function fixCases(){
    if($this->getRootInstance()){
      $this->setSlugSingularName($this->fixedSlugSingularName());
      $this->setSlugPluralName($this->fixedSlugPluralName());
      $this->setSnakeSingularName($this->fixedSnakeSingularName());
      $this->setSnakePluralName($this->fixedSnakePluralName());
      $this->setCamelSingularName($this->fixedCamelSingularName());
      $this->setCamelPluralName($this->fixedCamelPluralName());
      $this->setStudlySingularName($this->fixedStudlySingularName());
      $this->setStudlyPluralName($this->fixedStudlyPluralName());
    }
    return $this;
  }

  public function fixedSlugSingularName($name = null){
    if($name)
      return Str::snake($name,'-');
    return $this->getRootInstance()->getSlugSingularName();
  }

  public function fixedSlugPluralName($name = null){
    if($name)
      return Str::plural(Str::snake($name,'-'));
    return Str::plural($this->getRootInstance()->getSlugSingularName());
  }

  public function fixedCamelSingularName($name = null){
    if($name)
      return Str::camel($name);
    return Str::camel($this->getRootInstance()->getSlugSingularName());
  }

  public function fixedCamelPluralName($name = null){
    if($name)
      return Str::plural(Str::camel($name));
    return Str::plural(Str::camel($this->getRootInstance()->getSlugSingularName()));
  }

  public function fixedSnakeSingularName($name = null){
    if($name)
      return Str::snake($name);
    return Str::snake($this->getRootInstance()->getSlugSingularName());
  }

  public function fixedSnakePluralName($name = null){
    if($name)
      return Str::plural(Str::snake($name));
    return Str::plural(Str::snake($this->getRootInstance()->getSlugSingularName()));
  }

  public function fixedStudlySingularName($name = null){
    if($name)
      return Str::studly($name);
    return Str::studly($this->getRootInstance()->getSlugSingularName());
  }

  public function fixedStudlyPluralName($name = null){
    if($name)
      return Str::plural(Str::studly($name));
    return Str::plural(Str::studly($this->getRootInstance()->getSlugSingularName()));
  }
  public function loadController($controller=null){
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
  public function loadPaginator($paginator=null){
    if(is_object($paginator))
      return $this->setPaginatorClass(get_class($paginator))->setPaginatorInstance($paginator);

    if(class_exists($paginator))
      return $this->setPaginatorInstance(new $paginator($this));

    $paginatorMode = $this->getRequestInstance()->get("paginate");
    $paginatorClass = $this->getRootInstance()->getPaginator($paginatorMode['searchMode']??null);
    return $this->setPaginatorInstance(new $paginatorClass($this));
  }
  public function generateModel(){
    if(!($controller = $this->getRootInstance()))
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
    if(!($controller = $this->getRootInstance()))
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
  public function assignUser(){
    if(!($user = \Auth::user()))
      return $this;
    $this->setUserModelBuilderInstance($this->getUserModelClass()::id($user->id));
    $this->setUserModelCollectionInstance($this->getUserModelBuilderInstance()->first());
    return $this;
  }
  public function fixActionResource(){
    $this->setActionResource($this->getSlugPluralName().".".Str::snake($this->getCurrentAction(),'-'));
    return $this;
  }
  public function actionAccess(){
    $currentActionAccess = $this->actionsAccess[$this->getActionResource()] ?? null;
    if($currentActionAccess !== null)
      return $currentActionAccess;
    if(
      !$this->getUserModelBuilderInstance() ||
      !$this->getUserModelCollectionInstance() ||
      !$this->getUserModelCollectionInstance()->active
    )
      return $this->actionsAccess[$this->getActionResource()] = false;
    if($this->getUserModelCollectionInstance()->isRoot())
      return $this->actionsAccess[$this->getActionResource()] = true;
    if(!$this->getPermissionModelClass::action($this->getActionResource())->count())
      return $this->actionsAccess[$this->getActionResource()] = true;
    if(kageBunshinNoJutsu($this->getUserModelBuilderInstance())->actionPermission($this->getActionResource())->count())
      return $this->actionsAccess[$this->getActionResource()] = true;
    return $this->actionsAccess[$this->getActionResource()] = false;
  }
}
