<?php namespace Crudvel\Traits;

/*
    This is a customization of the controller, some controllers needs to save multiple data on different tables in one step, doing that without transaction is a bad idea.
    so the options is to doing it manually, but it is a lot of code, and it is always the same, to i get that code and put it togheter as methods, so now with the support of
    the anonymous functions, all this code can be reused, saving a lot of time.
*/
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

trait CrudTrait {
  //Getters start
  public function getCamelPluralName(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getCamelPluralName():
      $this->getCvResourceClass()::getCamelPluralName();
  }
  public function getCamelSingularName(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getCamelSingularName():
      $this->getCvResourceClass()::getCamelSingularName();
  }
  public function getSlugPluralName(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getSlugPluralName():
      $this->getCvResourceClass()::getSlugPluralName();
  }
  //this is the most important case, because all the resource name strategy depends on it
  public function getSlugSingularName(){
    if(!empty($this->cvResourceInstance) && ($slugSingularName=$this->cvResourceInstance->getSlugSingularName()))
      return $slugSingularName;
    return !empty($this->slugSingularName)?
      $this->slugSingularName:
      \Str::snake(str_replace('Controller','',class_basename($this)),'-');
  }
  public function getSnakePluralName(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getSnakePluralName():
      $this->getCvResourceClass()::getSnakePluralName();
  }
  public function getSnakeSingularName(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getSnakeSingularName():
      $this->getCvResourceClass()::getSnakeSingularName();
  }
  public function getStudlyPluralName(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getStudlyPluralName():
      $this->getCvResourceClass()::getStudlyPluralName();
  }
  public function getStudlySingularName(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getStudlySingularName():
      $this->getCvResourceClass()::getStudlySingularName();
  }
  //-----------
  public function getControllerClass(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getControllerClass():
      $this->getCvResourceClass()::getControllerClass();
  }
  public function getControllerInstance(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getControllerInstance():
      $this->getCvResourceClass()::getControllerInstance();
  }
  public function getModelClass(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getModelClass():
      $this->getCvResourceClass()::getModelClass();
  }
  public function getModelBuilderInstance(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getModelBuilderInstance():
      $this->getCvResourceClass()::getModelBuilderInstance();
  }
  public function getModelCollectionInstance(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getModelCollectionInstance():
      $this->getCvResourceClass()::getModelCollectionInstance();
  }
  public function getMainTable(){
    return $this->modelInstanciator(true)->getTable();
  }
  public function getRequestClass(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getRequestClass():
      $this->getCvResourceClass()::getRequestClass();
  }
  public function getRequestInstance(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getRequestInstance():
      $this->getCvResourceClass()::getRequestInstance();
  }
  public function getUserModelClass(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getUserModelClass():
      $this->getCvResourceClass()::getUserModelClass();
  }
  public function getUserModelBuilderInstance(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getUserModelBuilderInstance():
      $this->getCvResourceClass()::getUserModelBuilderInstance();
  }
  public function getUserModelCollectionInstance(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getUserModelCollectionInstance():
      $this->getCvResourceClass()::getUserModelCollectionInstance();
  }
  public function getPermissionModelClass(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getPermissionModelClass():
      $this->getCvResourceClass()::getPermissionModelClass();
  }
  public function getPaginatorClass(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getPaginatorClass():
      $this->getCvResourceClass()::getPaginatorClass();
  }
  public function getPaginatorInstance(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getPaginatorInstance():
      $this->getCvResourceClass()::getPaginatorInstance();
  }
  public function getRootInstance(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getRootInstance():
      $this->getCvResourceClass()::getRootInstance();
  }
  public function getCvResourceClass(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getRootInstance()->cvResourceClass():
      'CvResource';
  }
  public function getCvResourceInstance(){
    return $this->cvResourceInstance??null;
  }
  //-----------
  public function getRows(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getRows():
      $this->getCvResourceClass()::getRows();
  }
  public function getRow(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getRow():
      $this->getCvResourceClass()::getRow();
  }
  public function getCurrentAction(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getCurrentAction():
      $this->getCvResourceClass()::getCurrentAction();
  }
  public function getCurrentActionKey(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getCurrentActionKey():
      $this->getCvResourceClass()::getCurrentActionKey();
  }
  public function getActionResource(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getActionResource():
      $this->getCvResourceClass()::getActionResource();
  }
  public function getFields(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getFields():
      $this->getCvResourceClass()::getFields();
  }
  public function getPaginateFields(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getPaginateFields():
      $this->getCvResourceClass()::getPaginateFields();
  }
  public function getLangName(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getLangName():
      $this->getCvResourceClass()::getLangName();
  }
  //-----------
  public function getActions(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getActions():
      $this->getCvResourceClass()::getActions();
  }
  public function getViewActions(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getViewActions():
      $this->getCvResourceClass()::getViewActions();
  }
  public function getRowActions(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getRowActions():
      $this->getCvResourceClass()::getRowActions();
  }
  public function getRowsActions(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->getRowsActions():
      $this->getCvResourceClass()::getRowsActions();
  }
  //Getters end

  //Setters start
  public function setCamelPluralName($camelPluralName=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setCamelPluralName($camelPluralName);
    else
      $this->getCvResourceClass()::setCamelPluralName($camelPluralName);
    return $this;
  }
  public function setCamelSingularName($camelSingularName=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setCamelSingularName($camelSingularName);
    else
      $this->getCvResourceClass()::setCamelSingularName($camelSingularName);
    return $this;
  }
  public function setSlugPluralName($slugPluralName=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setSlugPluralName($slugPluralName);
    else
      $this->getCvResourceClass()::setSlugPluralName($this);
    return $this;
  }
  public function setSlugSingularName($slugSingularName=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setSlugSingularName($slugSingularName);
    else
      $this->getCvResourceClass()::setSlugSingularName($slugSingularName);
    return $this;
  }
  public function setSnakePluralName($snakePluralName=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setSnakePluralName($snakePluralName);
    else
      $this->getCvResourceClass()::setSnakePluralName($snakePluralName);
    return $this;
  }
  public function setSnakeSingularName($snakeSingularName=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setSnakeSingularName($snakeSingularName);
    else
      $this->getCvResourceClass()::setSnakeSingularName($snakeSingularName);
    return $this;
  }
  public function setStudlyPluralName($studlyPluralName=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setStudlyPluralName($studlyPluralName);
    else
      $this->getCvResourceClass()::setStudlyPluralName($studlyPluralName);
    return $this;
  }
  public function setStudlySingularName($studlySingularName=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setStudlySingularName($studlySingularName);
    else
      $this->getCvResourceClass()::setStudlySingularName($studlySingularName);
    return $this;
  }
  //-----------
  public function setControllerClass($controllerClass=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setControllerClass($controllerClass);
    else
      $this->getCvResourceClass()::setControllerClass($controllerClass);
    return $this;
  }
  public function setControllerInstance($controllerInstance=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setControllerInstance($controllerInstance);
    else
      $this->getCvResourceClass()::setControllerInstance($controllerInstance);
    return $this;
  }
  public function setModelClass($modelClass=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setModelClass($modelClass);
    else
      $this->getCvResourceClass()::setModelClass($this);
    return $this;
  }
  public function setModelBuilderInstance($modelBuilderInstance=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setModelBuilderInstance($modelBuilderInstance);
    else
      $this->getCvResourceClass()::setModelBuilderInstance($modelBuilderInstance);
    return $this;
  }
  public function setModelCollectionInstance($modelCollectionInstance=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setModelCollectionInstance($modelCollectionInstance);
    else
      $this->getCvResourceClass()::setModelCollectionInstance($modelCollectionInstance);
    return $this;
  }
  public function setRequestClass($requestClass=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setRequestClass($requestClass);
    else
      $this->getCvResourceClass()::setRequestClass($this);
    return $this;
  }
  public function setRequestInstance($requestInstance=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setRequestInstance($requestInstance);
    else
      $this->getCvResourceClass()::setRequestInstance($requestInstance);
    return $this;
  }
  public function setUserModelClass($userModelClass=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setUserModelClass($userModelClass);
    else
      $this->getCvResourceClass()::setUserModelClass($this);
    return $this;
  }
  public function setUserModelBuilderInstance($userModelBuilderInstance=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setUserModelBuilderInstance($userModelBuilderInstance);
    else
      $this->getCvResourceClass()::setUserModelBuilderInstance($userModelBuilderInstance);
    return $this;
  }
  public function setUserModelCollectionInstance($userModelCollectionInstance=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setUserModelCollectionInstance($userModelCollectionInstance);
    else
      $this->getCvResourceClass()::setUserModelCollectionInstance($userModelCollectionInstance);
    return $this;
  }
  public function setPermissionModelClass($permissionModelClass=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setPermissionModelClass($permissionModelClass);
    else
      $this->getCvResourceClass()::setPermissionModelClass($this);
    return $this;
  }
  public function setPaginatorClass($paginatorClass=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setPaginatorClass($paginatorClass);
    else
      $this->getCvResourceClass()::setPaginatorClass($paginatorClass);
    return $this;
  }
  public function setPaginatorInstance($paginatorInstance=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setPaginatorInstance($paginatorInstance);
    else
      $this->getCvResourceClass()::setPaginatorInstance($paginatorInstance);
    return $this;
  }
  public function setRootInstance($rootInstance=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setRootInstance($rootInstance);
    else
      $this->getCvResourceClass()::setRootInstance($rootInstance);
    return $this;
  }
  //-----------
  public function setRows($rows=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setRows($rows);
    else
      $this->getCvResourceClass()::setRows($rows);
    return $this;
  }
  public function setRow($row=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setRow($row);
    else
      $this->getCvResourceClass()::setRow($row);
    return $this;
  }
  public function setCurrentAction($currentAction=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setCurrentAction($currentAction);
    else
      $this->getCvResourceClass()::setCurrentAction($currentAction);
    return $this;
  }
  public function setCurrentActionKey($currentActionKey=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setCurrentActionKey($currentActionKey);
    else
      $this->getCvResourceClass()::setCurrentActionKey($currentActionKey);
    return $this;
  }
  public function setActionResource($actionResource=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setActionResource($actionResource);
    else
      $this->getCvResourceClass()::setActionResource($actionResource);
    return $this;
  }
  public function setFields($fields=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setFields($fields);
    else
      $this->getCvResourceClass()::setFields($fields);
    return $this;
  }
  public function setPaginateFields($paginate=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setPaginateFields($paginate);
    else
      $this->getCvResourceClass()::setPaginateFields($paginate);
    return $this;
  }
  //consider to include and interface to ensure CvResource requiriments
  public function setCvResource($cvResourceInstance){
    $this->cvResourceInstance = $cvResourceInstance;
  }
  //-----------
  public function setActions($actions=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setActions($actions);
    else
      $this->getCvResourceClass()::setActions($actions);
    return $this;
  }
  public function setViewActions($viewActions=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setViewActions($viewActions);
    else
      $this->getCvResourceClass()::setViewActions($viewActions);
    return $this;
  }
  public function setRowActions($rowActions=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setRowActions($rowActions);
    else
      $this->getCvResourceClass()::setRowActions($rowActions);
    return $this;
  }
  public function setRowsActions($rowsActions=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setRowsActions($rowsActions);
    else
      $this->getCvResourceClass()::setRowsActions($rowsActions);
    return $this;
  }
  //Setters end
//-----
  public function injectCvResource(){
    //pdd($this->getCvResourceClass()::getCvResourceInstance());
    $this->cvResourceInstance = $this->getCvResourceClass()::getCvResourceInstance();
    return $this;
  }
  public function fixActionResource(){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->fixActionResource();
    else
      $this->getCvResourceClass()::fixActionResource();
    return $this;
  }
  public function specialAccess(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->specialAccess():
      $this->getCvResourceClass()::specialAccess();
  }

  public function actionAccess(){
    return $this->cvResourceInstance ?
      $this->cvResourceInstance->actionAccess():
      $this->getCvResourceClass()::actionAccess();
  }

  public function autoSetPropertys(...$propertyRewriter){
    if(!empty($propertyRewriter) && is_array($propertyRewriter))
      foreach ($propertyRewriter as $param)
        foreach ($param as $key => $value)
          if(property_exists ( $this , $key))
            $this->{$key} = $value;
  }
  public function modelInstanciator($new=false){
    $model = $this->getModelClass();
    if(!class_exists($model))
      return null;
    if($new)
      return new $model();
    return $model::noFilters();
  }

  public function loadFields(){
    if($this->cvResourceInstance && $this->cvResourceInstance->getRequestInstance())
      $this->setFields($this->cvResourceInstance->getRequestInstance()->all());
  }

  public function addField($field=null,$value=null){
    if($this->cvResourceInstance && $this->cvResourceInstance->getRequestInstance())
      $this->cvResourceInstance->addField($field,$value);
    return $this;
  }
  public function addAction(...$action){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->addAction($action);
    else
      $this->getCvResourceClass()::addAction($action);
    return $this;
  }
  public function addViewAction(...$viewAction){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->addViewAction($viewAction);
    else
      $this->getCvResourceClass()::addViewAction($viewAction);
    return $this;
  }
  public function addRowAction(...$rowAction){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->addRowAction($rowAction);
    else
      $this->getCvResourceClass()::addRowAction($rowAction);
    return $this;
  }
  public function addRowsAction(...$rowsAction){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->addRowsAction($rowsAction);
    else
      $this->getCvResourceClass()::addRowsAction($rowsAction);
    return $this;
  }

  public function removeField($field=null){
    if($this->cvResourceInstance && $this->cvResourceInstance->getRequestInstance())
      $this->cvResourceInstance->removeField($field);
    return $this;
  }

  public function removeAction($action=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->removeAction($action);
    else
      $this->getCvResourceClass()::removeAction($action);
    return $this;
  }

  public function removeViewAction($viewAction=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->removeViewAction($viewAction);
    else
      $this->getCvResourceClass()::removeViewAction($viewAction);
    return $this;
  }

  public function removeRowAction($rowAction=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->removeRowAction($rowAction);
    else
      $this->getCvResourceClass()::removeRowAction($rowAction);
    return $this;
  }

  public function removeRowsAction($rowsAction=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->removeRowsAction($rowsAction);
    else
      $this->getCvResourceClass()::removeRowsAction($rowsAction);
    return $this;
  }
  public function apiAlreadyExist($data=null){
    return response()->json(
      $data?
        $data:
        ["message"=>trans("crudvel.api.already_exist")],
        409
    );
  }

  public function apiUnautenticated($data=null){
    return response()->json($data?
      $data:
      ["message"=>trans("crudvel.api.unautorized")]
      ,401
    );
  }

  public function apiLoggetOut($data=null){
    return response()->json($data?
      $data:
      ["message"=>trans("crudvel.api.logget_out")]
      ,204
    );
  }

  public function apiUnautorized($data=null){
    return response()->json($data?
      $data:
      ["message"=>trans("crudvel.api.unautorized")]
      ,403
    );
  }

  public function apiNotFound($data=null){
    return response()->json($data?
      $data:
      ["message"=>trans("crudvel.api.not_found")]
      ,404
    );
  }

  public function apiSuccessResponse($data=null){
    return response()->json($data?
      $data:
      ["message"=>trans("crudvel.api.success")]
      ,200
    );
  }

  public function apiIncompleteResponse($data=null){
    return response()->json($data?
      $data:
      ["message"=>trans("crudvel.api.incomplete")]
      ,202
    );
  }

  public function apiFailResponse($data=null){
    return  response()->json($data?
      $data:
      ["message"=>trans("crudvel.api.transaction-error"),"error-message"=>trans("crudvel.api.operation_error")]
      ,400
    );
  }

  /**
   * general function for response when no found action
   *
   * @param array   responseProperties  array with propertys to rewrite response
   * propertys to rewrite: status, statusMessage, redirector, errors
   *
   * @author Benomas benomas@gmail.com
   * @date   2017-04-15
   * @return redirector
   */
  public function autoResponder($respProp=[]){

    if(empty($respProp))
    return Redirect::back()->withInput($this->fields);
    Session::flash(
      $respProp["message"]?$respProp["status"]:"success",
      $respProp["statusMessage"]?$respProp["statusMessage"]:"Correcto"
    );
    Session::flash("statusMessage",$respProp["statusMessage"]?$respProp["statusMessage"]:"Correcto");
    if(!empty($respProp["inputs"]))
      $allInputs = empty($respProp["inputs"])?$this->fields:$respProp["inputs"];

    $allInputs["lastAction"]   = $this->currentAction;
    $allInputs["lastActionId"] = $this->currentActionId;

    if(isset($respProp["withInput"])){
      $redirector=($respProp["redirector"]?$respProp["redirector"]:Redirect::back());
      if($respProp["withInput"])
        $redirector->withInput($allInputs);
    }
    else
      $redirector=$respProp["redirector"]?$respProp["redirector"]:Redirect::back();

    $redirector->withInput($allInputs);

    if(!empty($respProp["errors"]))
      Session::flash($errors, $respProp["errors"]);

    return $redirector;
  }

  /**
   * general function for response when no found action
   *
   * @param array   responseProperties  array with propertys to rewrite response
   * propertys to rewrite: status, statusMessage, redirector, errors
   *
   * @author Benomas benomas@gmail.com
   * @date   2017-04-15
   * @return redirector
   */
  public function webUnauthorized($respProp=[]){
    $respProp["status"]        = !empty($respProp["status"])?$respProp["status"]:"danger";
    $respProp["statusMessage"] = $this->messageResolver($respProp,"unautorized");
    $respProp["redirector"]    = !empty($respProp["redirector"])?$respProp["redirector"]:null;
    $respProp["errors"]        = !empty($respProp["errors"])?$respProp["errors"]:[];
    return $this->autoResponder($respProp);
  }

  /**
   * general function for response when no found action
   *
   * @param array   responseProperties  array with propertys to rewrite response
   * propertys to rewrite: status, statusMessage, redirector, errors
   *
   * @author Benomas benomas@gmail.com
   * @date   2017-04-15
   * @return redirector
   */
  public function webNotFound($respProp=[]){
    $respProp["status"]        = !empty($respProp["status"])?$respProp["status"]:"warning";
    $respProp["statusMessage"] = $this->messageResolver($respProp,"not_found");
    $respProp["redirector"]    = !empty($respProp["redirector"])?$respProp["redirector"]:null;
    $respProp["errors"]        = !empty($respProp["errors"])?$respProp["errors"]:[];
    return $this->autoResponder($respProp);
  }
  /**
   * general function for response when successResponse action
   *
   * @param array   responseProperties  array with propertys to rewrite response
   * propertys to rewrite: status, statusMessage, redirector, errors
   *
   * @author Benomas benomas@gmail.com
   * @date   2017-04-15
   * @return redirector
   */
  public function webSuccessResponse($respProp=[]){
    $respProp["status"]        = !empty($respProp["status"])?$respProp["status"]:"success";
    $respProp["statusMessage"] = $this->messageResolver($respProp,"success");
    $respProp["redirector"]    = !empty($respProp["redirector"])?$respProp["redirector"]:null;
    $respProp["errors"]        = !empty($respProp["errors"])?$respProp["errors"]:[];
    return $this->autoResponder($respProp);
  }
  /**
   * general function for response when failResponse action
   *
   * @param array   responseProperties  array with propertys to rewrite response
   * propertys to rewrite: status, statusMessage, redirector, errors
   *
   * @author Benomas benomas@gmail.com
   * @date   2017-04-15
   * @return redirector
   */
  public function webFailResponse($respProp=[]){
    $respProp["status"]        = !empty($respProp["status"])?$respProp["status"]:"danger";
    $respProp["statusMessage"] = $this->messageResolver($respProp,"transaction-error");
    $respProp["redirector"]    = !empty($respProp["redirector"])?$respProp["redirector"]:null;
    $respProp["errors"]        = !empty($respProp["errors"])?$respProp["errors"]:[];
    return $this->autoResponder($respProp);
  }
  /**
   * general function for make web response message
   *
   * @param array   responseProperties  array with propertys to rewrite response
   * propertys to rewrite: status, statusMessage, redirector, errors
   * @param string  web status
   * @author Benomas benomas@gmail.com
   * @date   2017-04-15
   * @return string message
   */
  public function messageResolver($respProp=[],$status=null){
    if(!empty($respProp["statusMessage"]))
    return $respProp["statusMessage"];

    if(in_array($status,["unautorized","not_found","transaction-error"]))
    return trans("crudvel.web.".$status);

    if($status==="success"){
      $label = in_array($this->currentAction,$this->rowsActions)?
        $this->rowsLabelTrans():
        $this->rowLabelTrans();
    return trans("crudvel.actions.".snake_case($this->currentAction).".success")." ".$label." ".trans("crudvel.actions_extra.common.correctly");
    }
    return trans("crudvel.web.not_found");
  }

  public function owner(){
    if($this->getUserModelCollectionInstance()->isRoot())
    return true;
    if($this->getUserModelCollectionInstance()->
      specialPermissions()->
      slug($this->cvResourceInstanceLangCase().".general-owner")->
      count()
    )
      $this->getModelBuilderInstance()->generalOwner($this->getUserModelCollectionInstance()->id);
    else
      if($this->getUserModelCollectionInstance()->specialPermissions()->slug($this->ngCase().".particular-owner")->count())
          $this->getModelBuilderInstance()->particularOwner($this->getUserModelCollectionInstance()->id);

    if(!$this->currentActionId)
    return true;

    $this->getModelBuilderInstance()->id($this->currentActionId);

    return $this->getModelBuilderInstance()->count();
  }
}
