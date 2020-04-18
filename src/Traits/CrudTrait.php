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
  public function getCvResourceInstance(){
    return $this->cvResourceInstance??null;
  }

  public function getCvResourceInstanceMethod($method=null,...$params){
    return $this->cvResourceInstance?
      $this->cvResourceInstance->{$method}(...$params):
      $this->getCvResourceClass()::{$method}(...$params);
  }

  public function setCvResourceInstanceMethod($method=null,...$params){
    $this->cvResourceInstance?
      $this->cvResourceInstance->{$method}(...$params):
      $this->getCvResourceClass()::{$method}(...$params);
    return $this;
  }

  public function getCamelPluralName(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getCamelSingularName(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getSlugPluralName(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  //this is the most important case, because all the resource name strategy depends on it
  public function getSlugSingularName(){
    if(!empty($this->getCvResourceInstance()) && ($slugSingularName=$this->getCvResourceInstance()->getSlugSingularName()))
      return $slugSingularName;
    return !empty($this->slugSingularName)?
      $this->slugSingularName:
      \Str::snake(str_replace('Controller','',class_basename($this)),'-');
  }

  public function getSnakePluralName(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getSnakeSingularName(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getStudlyPluralName(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getStudlySingularName(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }
  //-----------
  public function getControllerClass(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getControllerInstance(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getModelClass(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getModelBuilderInstance(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getModelCollectionInstance(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getMainTable(){
    return $this->getModelClass()::cvIam()->getTable();
  }

  public function getModelReference(){
    return $this->getModelClass()::cvIam();
  }

  public function getRequestClass(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getRequestInstance(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getUserModelClass(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getUserModelBuilderInstance(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getUserModelCollectionInstance(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getPermissionModelClass(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getPaginatorClass(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getPaginatorInstance(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getRootInstance(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getCvResourceClass(){
    return $this->getCvResourceInstance() ?
      $this->getCvResourceInstance()->getRootInstance()->cvResourceClass():
      'CvResource';
  }
  //-----------
  public function getRows(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getRow(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getCurrentAction(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getCurrentActionKey(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getActionResource(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getFields(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getPaginateFields(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getLangName(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getResourceAlias(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }
  //-----------
  public function getActions(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getViewActions(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getRowActions(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getRowsActions(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getSkipModelValidation(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getCallActionMethod(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getCallActionParameters(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getFlowControl(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function getPaginated(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }
  //Getters end

  //Setters start
  public function setCamelPluralName($camelPluralName=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$camelPluralName);
  }

  public function setCamelSingularName($camelSingularName=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$camelSingularName);
  }

  public function setSlugPluralName($slugPluralName=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$slugPluralName);
  }

  public function setSlugSingularName($slugSingularName=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$slugSingularName);
  }

  public function setSnakePluralName($snakePluralName=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$snakePluralName);
  }

  public function setSnakeSingularName($snakeSingularName=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$snakeSingularName);
  }

  public function setStudlyPluralName($studlyPluralName=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$studlyPluralName);
  }

  public function setStudlySingularName($studlySingularName=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$studlySingularName);
  }
  //-----------
  public function setCvResourceInstance($cvResourceInstance=null){
    return $this->cvResourceInstance=$cvResourceInstance??null;
  }

  public function setControllerClass($controllerClass=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$controllerClass);
  }

  public function setControllerInstance($controllerInstance=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$controllerInstance);
  }

  public function setModelClass($modelClass=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$modelClass);
  }

  public function setModelBuilderInstance($modelBuilderInstance=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$modelBuilderInstance);
  }

  public function setModelCollectionInstance($modelCollectionInstance=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$modelCollectionInstance);
  }

  public function setRequestClass($requestClass=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$requestClass);
  }

  public function setRequestInstance($requestInstance=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$requestInstance);
  }

  public function setUserModelClass($userModelClass=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$userModelClass);
  }

  public function setUserModelBuilderInstance($userModelBuilderInstance=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$userModelBuilderInstance);
  }

  public function setUserModelCollectionInstance($userModelCollectionInstance=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$userModelCollectionInstance);
  }

  public function setPermissionModelClass($permissionModelClass=null){
    if($this->cvResourceInstance)
      $this->cvResourceInstance->setPermissionModelClass($permissionModelClass);
    else
      $this->getCvResourceClass()::setPermissionModelClass($this);
    return $this;
  }

  public function setPaginatorClass($paginatorClass=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$paginatorClass);
  }

  public function setPaginatorInstance($paginatorInstance=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$paginatorInstance);
  }

  public function setRootInstance($rootInstance=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$rootInstance);
  }
  //-----------
  public function setRows($rows=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$rows);
  }

  public function setRow($row=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$row);
  }

  public function setCurrentAction($currentAction=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$currentAction);
  }

  public function setCurrentActionKey($currentActionKey=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$currentActionKey);
  }

  public function setActionResource($actionResource=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$actionResource);
  }

  public function setFields($fields=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$fields);
  }

  public function setPaginateFields($paginate=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$paginate);
  }

  public function setResourceAlias($resourceAlias=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$resourceAlias);
  }
  //consider to include and interface to ensure CvResource requiriments
  public function setCvResource($cvResourceInstance){
    $this->cvResourceInstance = $cvResourceInstance;
  }
  //-----------
  public function setActions($actions=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$actions);
  }

  public function setViewActions($viewActions=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$viewActions);
  }

  public function setRowActions($rowActions=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$rowActions);
  }

  public function setRowsActions($rowsActions=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$rowsActions);
  }

  public function setSkipModelValidation($skipModelValidation=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$skipModelValidation);
  }

  public function setCallActionMethod($callActionMethod=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$callActionMethod);
  }

  public function setCallActionParameters($callActionParameters=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$callActionParameters);
  }

  public function setFlowControl($flowControl=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$flowControl);
  }

  public function setPaginated($paginated=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$paginated);
  }
  //Setters end
//-----
  public function injectCvResource(){
    return $this->setCvResourceInstance($this->getCvResourceClass()::getCvResourceInstance());
  }

  public function fixActionResource(){
    $this->getCvResourceInstanceMethod(__FUNCTION__);
    return $this;
  }

  public function specialAccess(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function actionAccess($actionResource=null){
    return $this->getCvResourceInstanceMethod(__FUNCTION__,$actionResource);
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

  public function fixFlowControl(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function loadFields(){
    if($this->getCvResourceInstance() && $this->getCvResourceInstance()->getRequestInstance())
      return $this->setFields($this->getCvResourceInstance()->getRequestInstance()->all());
    return $this;
  }

  public function generateModelCollectionInstance(){
    return $this->getCvResourceInstanceMethod(__FUNCTION__);
  }

  public function addField($field=null,$value=null){
    if($this->getCvResourceInstance() && $this->getCvResourceInstance()->getRequestInstance())
      $this->getCvResourceInstance()->addField($field,$value);
    return $this;
  }

  public function addAction(...$action){
    return $this->setCvResourceInstanceMethod(__FUNCTION__.$action);
  }

  public function addViewAction(...$viewAction){
    return $this->setCvResourceInstanceMethod(__FUNCTION__.$viewAction);
  }

  public function addRowAction(...$rowAction){
    return $this->setCvResourceInstanceMethod(__FUNCTION__.$rowAction);
  }

  public function addRowsAction(...$rowsAction){
    return $this->setCvResourceInstanceMethod(__FUNCTION__.$rowsAction);
  }

  public function removeField($field=null){
    if($this->getCvResourceInstance() && $this->getCvResourceInstance()->getRequestInstance())
      $this->getCvResourceInstance()->removeField($field);
    return $this;
  }

  public function removeAction($action=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$action);
  }

  public function removeViewAction($viewAction=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$viewAction);
  }

  public function removeRowAction($rowAction=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$rowAction);
  }

  public function removeRowsAction($rowsAction=null){
    return $this->setCvResourceInstanceMethod(__FUNCTION__,$rowsAction);
  }

  public function clearFields(){
    if($this->getCvResourceInstance() && $this->getCvResourceInstance()->getRequestInstance())
      $this->getCvResourceInstance()->clearFields();
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

  public function apiUnproccesable($data=null){
    return response()->json($data?
      $data:
      ["message"=>trans("crudvel.api.unproccesable")]
      ,422
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
    return Redirect::back()->withInput($this->getFields());
    Session::flash(
      $respProp["message"]?$respProp["status"]:"success",
      $respProp["statusMessage"]?$respProp["statusMessage"]:"Correcto"
    );
    Session::flash("statusMessage",$respProp["statusMessage"]?$respProp["statusMessage"]:"Correcto");
    if(!empty($respProp["inputs"]))
      $allInputs = empty($respProp["inputs"])?$this->getFields():$respProp["inputs"];

    $allInputs["lastAction"]   = $this->getCurrentAction();
    $allInputs["lastActionId"] = $this->getCurrentActionKey();

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
      $label = in_array($this->getCurrentAction(),$this->getRowsActions())?
        $this->rowsLabelTrans():
        $this->rowLabelTrans();
    return trans("crudvel.actions.".cvSnakeCase($this->getCurrentAction()).".success")." ".$label." ".trans("crudvel.actions_extra.common.correctly");
    }
    return trans("crudvel.web.not_found");
  }

  public function owner(){
    if($this->getUserModelCollectionInstance()->isRoot())
      return true;

    if($this->getUserModelCollectionInstance()->specialPermissions()->slug($this->getSlugPluralName().".general-owner")->count())
      return true;

    if($this->getUserModelCollectionInstance()->specialPermissions()->slug($this->getSlugPluralName().".particular-owner")->count())
      return true;

    if(!$this->getCurrentActionKey())
      return true;

    return $this->getModelBuilderInstance()->count();
  }

  public function ownerOld(){
    if($this->getUserModelCollectionInstance()->isRoot())
      return true;
    if($this->getUserModelCollectionInstance()->
      specialPermissions()->
      slug($this->getSlugPluralName().".general-owner")->
      count()
    ){
      $this->getModelBuilderInstance()->generalOwner($this->getUserModelCollectionInstance()->id);
    }else{
      if($this->getUserModelCollectionInstance()->specialPermissions()->slug($this->getSlugPluralName().".particular-owner")->count())
        $this->getModelBuilderInstance()->particularOwner($this->getUserModelCollectionInstance()->id);
    }
    if(!$this->getCurrentActionKey())
      return true;

    $this->getModelBuilderInstance()->id($this->getCurrentActionKey());

    return $this->getModelBuilderInstance()->count();
  }

  public function selfPreBuilder($alias=null){
    if(!$alias){
      customLog('alias required');
      die('alias required');
    }
    $table = $this->getMainTable();
    return $this->getModelClass()::withoutGlobalScopes()->from("$table as $alias")
    ->whereColumn("$alias.id", "$table.id")
    ->limit(1);
  }

  /**
  * this function is defined to work only with
  *
  * @author benomas benomas@gmail.com
  * @date   2019-12-18
  * @return void
  */
  public function defCvSearch(){
    return $this->selfPreBuilder('self')->selectRaw("CONCAT(self.name, '')");
  }
}
