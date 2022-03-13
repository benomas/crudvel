<?php

namespace Crudvel\Libraries\CvResource;

use Illuminate\Support\Str;

class CvResource
{
  use \Crudvel\Traits\CvResourceTrait;
  use \Crudvel\Libraries\Helpers\CasesTrait;

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
  protected $resourceAlias;

  protected $rowActions;
  protected $viewActions;
  protected $rowsActions;
  protected $flowControl;

  protected $specialFilterInstance  = null;
  protected $specialColumnsInstance  = null;
  protected $safeCollectionInstance = null;
  protected $preFlowControlInstance = null;
  protected $prePaginatorInstance   = null;
  protected $adderInstance          = null;

// [Specific Logic]
  public function __construct(){
  }

  public function boot($controllerInstance=null){
    if(!$controllerInstance)
      return;

    $params = $this->setControllerInstance($controllerInstance)
      ->setControllerClass(get_class($controllerInstance))
      ->getControllerInstance()
      ->setCvResourceInstance($this)
      ->getRootInstance()
      ->getCallActionParameters();

    return $this->assignUser()->fixCases()
      ->setCurrentActionKey($params[$this->getSnakeSingularName()]??null)
      ->setCurrentAction($this->getRootInstance()->getCallActionMethod())
      ->loadModel()->loadRequest()->loadPaginator();
  }

  public function startModelBuilderInstance(){
    if($this->modelClass)
      return $this->modelBuilderInstance = new $this->modelClass;

    return $this;
  }

  public function fixCases(){
    if($this->getRootInstance()){
      $this->setSlugSingularName($this->cvSlugCaseSingularName());
      $this->setSlugPluralName($this->cvSlugCasePluralName());
      $this->setSnakeSingularName($this->cvSnakeCaseSingularName());
      $this->setSnakePluralName($this->cvSnakeCasePluralName());
      $this->setCamelSingularName($this->fixedCamelSingularName());
      $this->setCamelPluralName($this->fixedCamelPluralName());
      $this->setStudlySingularName($this->fixedStudlySingularName());
      $this->setStudlyPluralName($this->fixedStudlyPluralName());
    }

    return $this;
  }

  public function cvSlugCaseSingularName($name = null){
    if($name)
      return $this->cvCaseFixer('kebab|slug',$name);

    return $this->getRootInstance()->getSlugSingularName();
  }

  public function cvSlugCasePluralName($name = null){
    if($name)
      return $this->cvCaseFixer('kebab|slug|plural',$name);

    return $this->cvPluralCase(($this->getRootInstance()->getSlugSingularName()));
  }

  public function fixedCamelSingularName($name = null){
    return $this->cvCamelCase($name??$this->getRootInstance()->getSlugSingularName());
  }

  public function fixedCamelPluralName($name = null){
    return $this->cvCaseFixer('plural|camel',$name??$this->getRootInstance()->getSlugSingularName());
  }

  public function cvSnakeCaseSingularName($name = null){
    return $this->cvCaseFixer('camel|snake',$name??$this->getRootInstance()->getSlugSingularName());
  }

  public function cvSnakeCasePluralName($name = null){
    return $this->cvCaseFixer('plural|camel|snake',$name??$this->getRootInstance()->getSlugSingularName());
  }

  public function fixedStudlySingularName($name = null){
    return $this->cvStudlyCase($name??$this->getRootInstance()->getSlugSingularName());
  }

  public function fixedStudlyPluralName($name = null){
    return $this->cvCaseFixer('plural|studly',$name??$this->getRootInstance()->getSlugSingularName());
  }

  public function loadController($controller=null){
    if(!is_object($controller))
      return $this;

    return $this->setControllerClass(get_class($controller))->setControllerBuilderInstance($controller);

    if(!$controller || !class_exists($controller))
      return $this->generateController();

    return $this->setControllerClass($controller)->setControllerBuilderInstance($controller);
  }

  public function loadModel($model=null){
    if(is_object($model)){
      $model->noFilters();
      return $this->setModelClass(get_class($model))->setModelBuilderInstance($model);
    }

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

  public function generateSpecialFilterInstance($specialFilterInstance=null){
    if(is_object($specialFilterInstance))
      return $this->setSpecialFilterInstance($specialFilterInstance);

    if(is_object($this->getSpecialFilterInstance()))
      return $this;

    if(!($controller = $this->getRootInstance()))
      return $this;

    if($controller->getSpecialFilterInstance() && class_exists($controller->getSpecialFilterInstance()))
      return $this;

    if(!$studlySingularName = $this->getStudlySingularName())
      return $this;

    $specialFilterClass="App\Http\Auxiliars\SpecialFilters\\{$studlySingularName}SpecialFilter";

    if(!class_exists($specialFilterClass))
      return $this;

    return $this->setSpecialFilterInstance(new $specialFilterClass());
  }

  public function generateSpecialColumnsInstance($specialColumnsInstance=null){
    if(is_object($specialColumnsInstance))
      return $this->setSpecialFilterInstance($specialColumnsInstance);

    if(is_object($this->getSpecialColumnsInstance()))
      return $this;

    if(!($controller = $this->getRootInstance()))
      return $this;

    if($controller->getSpecialColumnsInstance() && class_exists($controller->getSpecialColumnsInstance()))
      return $this;

    if(!$studlySingularName = $this->getStudlySingularName())
      return $this;

    $specialColumnsClass="App\Http\Auxiliars\SpecialColumnss\\{$studlySingularName}SpecialColumns";

    if(!class_exists($specialColumnsClass))
      return $this;

    return $this->setSpecialColumnsInstance(new $specialColumnsClass());
  }

  public function generateSafeCollectionInstance($safeCollectionInstance=null){
    if(is_object($safeCollectionInstance))
      return $this->setSafeCollectionInstance($safeCollectionInstance);

    if(is_object($this->getSafeCollectionInstance()))
      return $this;

    if(!($controller = $this->getRootInstance()))
      return $this;

    if($controller->getSafeCollectionInstance() && class_exists($controller->getSafeCollectionInstance()))
      return $this;

    if(!$studlySingularName = $this->getStudlySingularName())
      return $this;

    $safeCollectionClass="App\Http\Auxiliars\SafeCollections\\{$studlySingularName}SafeCollection";

    if(!class_exists($safeCollectionClass))
      return $this;

    return $this->setSafeCollectionInstance(new $safeCollectionClass());
  }

  public function generatePreFlowControlInstance($preFlowControlInstance=null){
    if(is_object($preFlowControlInstance))
      return $this->setPreFlowControlInstance($preFlowControlInstance);

    if(is_object($this->getPreFlowControlInstance()))
      return $this;

    if(!($controller = $this->getRootInstance()))
      return $this;

    if($controller->getPreFlowControlInstance() && class_exists($controller->getPreFlowControlInstance()))
      return $this;

    if(!$studlySingularName = $this->getStudlySingularName())
      return $this;

    $preFlowControlClass="App\Http\Auxiliars\PreFlowControls\\{$studlySingularName}PreFlowControl";

    if(!class_exists($preFlowControlClass))
      return $this;

    return $this->setPreFlowControlInstance(new $preFlowControlClass());
  }

  public function generatePrePaginatorInstance($prePaginatorInstance=null){
    if(is_object($prePaginatorInstance))
      return $this->setPrePaginatorInstance($prePaginatorInstance);

    if(is_object($this->getPrePaginatorInstance()))
      return $this;

    if(!($controller = $this->getRootInstance()))
      return $this;

    if($controller->getPrePaginatorInstance() && class_exists($controller->getPrePaginatorInstance()))
      return $this;

    if(!$studlySingularName = $this->getStudlySingularName())
      return $this;

    $prePaginatorClass="App\Http\Auxiliars\PrePaginators\\{$studlySingularName}PrePaginator";

    if(!class_exists($prePaginatorClass))
      return $this;

    return $this->setPrePaginatorInstance(new $prePaginatorClass());
  }

  public function generateAdderInstance($adderInstance=null){
    if(is_object($adderInstance))
      return $this->setAdderInstance($adderInstance);

    if(is_object($this->getAdderInstance()))
      return $this;

    if(!($controller = $this->getRootInstance()))
      return $this;

    if($controller->getAdderInstance() && class_exists($controller->getAdderInstance()))
      return $this;

    if(!$studlySingularName = $this->getStudlySingularName())
      return $this;

    $adderClass="App\Http\Auxiliars\Adders\\{$studlySingularName}Adder";

    if(!class_exists($adderClass))
      return $this;

    return $this->setAdderInstance(new $adderClass());
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

  public function generateModelCollectionInstance($key=null){
    if($key || in_array($this->getCurrentAction(),$this->getRowActions())){
      $key = $key ?? $this->getCurrentActionKey();

      return $this->setModelCollectionInstance($this->getModelBuilderInstance()->cvResourceFilter($key)->first());
    }

    return $this;
  }

  public function solveBeforesFlowControl(){
    $this->generatePreFlowControlInstance();

    $this->getRootInstance()->beforeFlowControl();

    if($this->getPreFlowControlInstance() && method_exists($this->getPreFlowControlInstance(),'beforeFlowControl')){
      $this->getPreFlowControlInstance()->beforeFlowControl();
    }

    if(method_exists($this->getRootInstance(),"{$this->getCurrentAction()}BeforeFlowControl")){
      $this->getRootInstance()->{$this->getCurrentAction().'BeforeFlowControl'}();
    }

    if($this->getPreFlowControlInstance() && method_exists($this->getPreFlowControlInstance(),"{$this->getCurrentAction()}BeforeFlowControl")){
      $this->getPreFlowControlInstance()->{"{$this->getCurrentAction()}BeforeFlowControl"}();
    }

    return $this;
  }

  public function solveBeforesPaginate($method,$parameters){
    $this->generatePrePaginatorInstance();

    $this->getRootInstance()->beforePaginate($method,$parameters);

    if($this->getPrePaginatorInstance() && method_exists($this->getPrePaginatorInstance(),'beforePaginate')){
      $this->getPrePaginatorInstance()->beforePaginate($parameters);
    }

    if(method_exists($this->getRootInstance(),$this->getCurrentAction().'BeforePaginate')){
      $this->getRootInstance()->{$this->getCurrentAction().'BeforePaginate'}($parameters);
    }

    if($this->getPrePaginatorInstance() && method_exists($this->getPrePaginatorInstance(),"{$this->getCurrentAction()}BeforePaginate")){
      $this->getPrePaginatorInstance()->{"{$this->getCurrentAction()}BeforePaginate"}($parameters);
    }

    return $this;
  }

  public function solveSafeCollection($data=null){
    if(!$data)
      return $data;

    if($data instanceof \Illuminate\Support\Collection){
      $collection = $data;
    }else{
      $collection = $data['data'] ?? null;

      if(
        !$collection ||
        (
          !$collection instanceof \Illuminate\Support\Collection &&
          !$collection instanceof \Illuminate\Database\Eloquent\Model
        )
      )
        return $data;
    }

    $this->generateSafeCollectionInstance();
    if($this->getSafeCollectionInstance() && method_exists($this->getSafeCollectionInstance(),"{$this->getCurrentAction()}SafeCollection")){
      $this->getSafeCollectionInstance()->{"{$this->getCurrentAction()}SafeCollection"}($collection);
    }else{
      if($this->getSafeCollectionInstance() && method_exists($this->getSafeCollectionInstance(),'safeCollection')){
        $this->getSafeCollectionInstance()->safeCollection($collection);
      }
    }

    if($collection && $collection->count() && $this->getPaginatorInstance()){
      $dataArray = $collection->first();

      if(!is_array($dataArray))
        $dataArray = $dataArray->toArray();

      $allColumns   = collect(array_keys($dataArray));
      if(
        $this->getPaginatorInstance()->getCollectionsIncludes() &&
        $this->getPaginatorInstance()->getCollectionsIncludes()->count()
      ){
        $collection->makeHidden(
          $allColumns->diff($this->getPaginatorInstance()->getCollectionsIncludes())->toArray()
        );

        return $data;
      }

      if(
        $this->getPaginatorInstance()->getCollectionsExcludes() &&
        $this->getPaginatorInstance()->getCollectionsExcludes()->count()
      )
        $collection->makeHidden($this->getPaginatorInstance()->getCollectionsExcludes()->toArray());
    }

    return $data;
  }

  public function fixFlowControl(){
    $this->getRootInstance()->loadFields();
    $this->solveBeforesFlowControl()
      ->getRootInstance()
      ->emptyModelBuilderException()
      ->invalidActionException()
      ->disableInactiveRows()
      ->preAction(
        $this->getCallActionParameters(),
        $this->getSnakeSingularName()
      );

    $this->generateModelCollectionInstance()
      ->getRootInstance()
      ->emptyRowActionException()
      ->emptyRowsActionException();

    return $this;
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
    if ($this->getUserModelCollectionInstance())
      return $this;

    if(!($user = \Auth::user()))
      return $this;

    $this->setUserModelBuilderInstance($this->getUserModelClass()::disableRestricction()->key($user->id));

    $this->setUserModelCollectionInstance($this->getUserModelBuilderInstance()->first());

    return $this;
  }

  public function fixActionResource(){
    $this->setActionResource($this->getSlugPluralName().".".Str::snake($this->getCurrentAction(),'-'));

    return $this;
  }

	public function specialAccess($special){
    if(!$this->getUserModelBuilderInstance() || !$this->getUserModelCollectionInstance())
      return false;

    if($this->getUserModelCollectionInstance()->isRoot())
      return true;

    if(!$this->getPermissionModelClass()::disableRestricction()->special($special)->count())
      return true;

    return kageBunshinNoJutsu($this->getUserModelBuilderInstance())->specialPermission($special)->count() > 0;
  }

  public function actionAccess($actionResource = null){
    $actionResource = $actionResource ?? $this->getActionResource();
    $currentActionAccess = $this->actionsAccess[$actionResource] ?? null;

    if($currentActionAccess !== null)
      return $currentActionAccess;

    if(
      !$this->getUserModelBuilderInstance() ||
      !$this->getUserModelCollectionInstance() ||
      !$this->getUserModelCollectionInstance()->active
    )
      return $this->actionsAccess[$actionResource] = false;

    if($this->getUserModelCollectionInstance()->isRoot())
      return $this->actionsAccess[$actionResource] = true;

    if(!$this->getPermissionModelClass()::disableRestricction()->action($actionResource)->count())
      return $this->actionsAccess[$actionResource] = true;

    if(kageBunshinNoJutsu($this->getUserModelBuilderInstance())->actionPermission($actionResource)->count())
      return $this->actionsAccess[$actionResource] = true;

    return $this->actionsAccess[$actionResource] = false;
  }

  public function addField($field=null,$value=null){
    if($field)
      $this->fields[$field]=$value;

    return $this;
  }

  public function addAction($action=null){
    if($action){
      if(is_array($action))
        $this->setActions(array_merge((array) $this->getActions(),$action));
      else
        $this->actions[]=$action;
    }

    return $this;
  }
  public function addViewAction($viewAction=null){
    if($viewAction){
      if(is_array($viewAction))
        $this->setViewActions(array_merge((array) $this->getActions(),$viewAction));
      else
        $this->viewActions[]=$viewAction;
    }

    return $this;
  }
  public function addRowAction($rowAction=null){
    if($rowAction){
      if(is_array($rowAction))
        $this->setRowActions(array_merge((array) $this->getActions(),$rowAction));
      else
        $this->rowActions[]=$rowAction;
    }

    return $this;
  }
  public function addRowsAction($rowsAction=null){
    if($rowsAction){
      if(is_array($rowsAction))
        $this->setRowsActions(array_merge((array) $this->getActions(),$rowsAction));
      else
        $this->rowsActions[]=$rowsAction;
    }

    return $this;
  }

  public function removeField($field=null){
    if($field !== null)
      unset($this->fields[$field]);

    return $this;
  }

  public function clearFields(){
    $this->fields = [];

    return $this;
  }

  public function removeAction($action=null){
    if (($key = array_search($action, $this->actions)) !== false)
      unset($this->actions[$key]);

    return $this;
  }

  public function removeViewAction($viewAction=null){
    if (($key = array_search($viewAction, $this->viewActions)) !== false)
      unset($this->viewActions[$key]);

    return $this;
  }

  public function removeRowAction($rowAction=null){
    if (($key = array_search($rowAction, $this->rowActions)) !== false)
      unset($this->rowActions[$key]);

    return $this;
  }

  public function removeRowsAction($rowsAction=null){
    if (($key = array_search($rowsAction, $this->rowsActions)) !== false)
      unset($this->rowsActions[$key]);

    return $this;
  }

  public function extractPaginateFields(){
    return $this->setPaginateFields($this->fields['b64Query']['paginate'] ?? null);
  }
// [End Specific Logic]
}
