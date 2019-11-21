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
      return $this->cvResource ?
        $this->cvResource->getCamelPluralName():
        \CvResource::getCamelPluralName();
  }
  public function getCamelSingularName(){
      return $this->cvResource ?
        $this->cvResource->getCamelSingularName():
        \CvResource::getCamelSingularName();
  }
  public function getSlugPluralName(){
      return $this->cvResource ?
        $this->cvResource->getSlugPluralName():
        \CvResource::getSlugPluralName();
  }
  //this is the most important case, because all the resource name strategy depends on it
  public function getSlugSingularName(){
    if(!empty($this->cvResource) && ($slugSingularName=$this->cvResource()->getSlugSingularName()))
      return $slugSingularName;
    return !empty($this->slugSingularName)?
      $this->slugSingularName:
      \Illuminate\Support\Str::snake(str_replace('Controller','',class_basename($this)),'-');
  }
  public function getSnakePluralName(){
      return $this->cvResource ?
        $this->cvResource->getSnakePluralName():
        \CvResource::getSnakePluralName();
  }
  public function getSnakeSingularName(){
      return $this->cvResource ?
        $this->cvResource->getSnakeSingularName():
        \CvResource::getSnakeSingularName();
  }
  public function getStudlyPluralName(){
      return $this->cvResource ?
        $this->cvResource->getStudlyPluralName():
        \CvResource::getStudlyPluralName();
  }
  public function getStudlySingularName(){
      return $this->cvResource ?
        $this->cvResource->getStudlySingularName():
        \CvResource::getStudlySingularName();
  }
  //-----------
  public function getControllerClass(){
      return $this->cvResource ?
        $this->cvResource->getControllerClass():
        \CvResource::getControllerClass();
  }
  public function getControllerInstance(){
      return $this->cvResource ?
        $this->cvResource->getControllerInstance():
        \CvResource::getControllerInstance();
  }
  public function getModelClass(){
      return $this->cvResource ?
        $this->cvResource->getModelClass():
        \CvResource::getModelClass();
  }
  public function getModelBuilderInstance(){
      return $this->cvResource ?
        $this->cvResource->getModelBuilderInstance():
        \CvResource::getModelBuilderInstance();
  }
  public function getModelCollectionInstance(){
      return $this->cvResource ?
        $this->cvResource->getModelCollectionInstance():
        \CvResource::getModelCollectionInstance();
  }
  public function getRequestClass(){
      return $this->cvResource ?
        $this->cvResource->getRequestClass():
        \CvResource::getRequestClass();
  }
  public function getRequestInstance(){
      return $this->cvResource ?
        $this->cvResource->getRequestInstance():
        \CvResource::getRequestInstance();
  }
  public function getUserModelClass(){
      return $this->cvResource ?
        $this->cvResource->getUserModelClass():
        \CvResource::getUserModelClass();
  }
  public function getUserModelBuilderInstance(){
      return $this->cvResource ?
        $this->cvResource->getUserModelBuilderInstance():
        \CvResource::getUserModelBuilderInstance();
  }
  public function getUserModelCollectionInstance(){
    return $this->cvResource ?
      $this->cvResource->getUserModelCollectionInstance():
      \CvResource::getUserModelCollectionInstance();
  }
  public function getPaginatorClass(){
    return $this->cvResource ?
      $this->cvResource->getPaginatorClass():
      \CvResource::getPaginatorClass();
  }
  public function getPaginatorInstance(){
    return $this->cvResource ?
      $this->cvResource->getPaginatorInstance():
      \CvResource::getPaginatorInstance();
  }
  public function getCvResourceInstance(){
    return $this->cvResource??null;
  }
  //-----------
  public function getRows(){
    return $this->cvResource ?
      $this->cvResource->getRows():
      \CvResource::getRows();
  }
  public function getRow(){
    return $this->cvResource ?
      $this->cvResource->getRow():
      \CvResource::getRow();
  }
  public function getCurrentAction(){
    return $this->cvResource ?
      $this->cvResource->getCurrentAction():
      \CvResource::getCurrentAction();
  }
  public function getCurrentActionKey(){
    return $this->cvResource ?
      $this->cvResource->getCurrentActionKey():
      \CvResource::getCurrentActionKey();
  }
  public function getFields(){
    return $this->cvResource ?
      $this->cvResource->getFields():
      \CvResource::getFields();
  }
  //Getters end

  //Setters start
  public function setCamelPluralName($camelPluralName=null){
    $this->cvResource ?
      $this->cvResource->setCamelPluralName($camelPluralName):
      \CvResource::setCamelPluralName($camelPluralName);
    return $this;
  }
  public function setCamelSingularName($camelSingularName=null){
    $this->cvResource ?
      $this->cvResource->setCamelSingularName($camelSingularName):
      \CvResource::setCamelSingularName($camelSingularName);
    return $this;
  }
  public function setSlugPluralName($slugPluralName=null){
    $this->cvResource ?
      $this->cvResource->setSlugPluralName($slugPluralName):
      \CvResource::setSlugPluralName($this);
    return $this;
  }
  public function setSlugSingularName($slugSingularName=null){
    $this->cvResource ?
      $this->cvResource->setSlugSingularName($slugSingularName):
      \CvResource::setSlugSingularName($slugSingularName);
    return $this;
  }
  public function setSnakePluralName($snakePluralName=null){
    $this->cvResource ?
      $this->cvResource->setSnakePluralName($snakePluralName):
      \CvResource::setSnakePluralName($snakePluralName);
    return $this;
  }
  public function setSnakeSingularName($snakeSingularName=null){
    $this->cvResource ?
      $this->cvResource->setSnakeSingularName($snakeSingularName):
      \CvResource::setSnakeSingularName($snakeSingularName);
    return $this;
  }
  public function setStudlyPluralName($studlyPluralName=null){
    $this->cvResource ?
      $this->cvResource->setStudlyPluralName($studlyPluralName):
      \CvResource::setStudlyPluralName($studlyPluralName);
    return $this;
  }
  public function setStudlySingularName($studlySingularName=null){
    $this->cvResource ?
      $this->cvResource->setStudlySingularName($studlySingularName):
      \CvResource::setStudlySingularName($studlySingularName);
    return $this;
  }
  //-----------
  public function setControllerClass($controllerClass=null){
    $this->cvResource ?
      $this->cvResource->setControllerClass($controllerClass):
      \CvResource::setControllerClass($controllerClass);
    return $this;
  }
  public function setControllerInstance($controllerInstance=null){
    $this->cvResource ?
      $this->cvResource->setControllerInstance($controllerInstance):
      \CvResource::setControllerInstance($controllerInstance);
    return $this;
  }
  public function setModelClass($modelClass=null){
    $this->cvResource ?
      $this->cvResource->setModelClass($modelClass):
      \CvResource::setModelClass($this);
    return $this;
  }
  public function setModelBuilderInstance($modelBuilderInstance=null){
    $this->cvResource ?
      $this->cvResource->setModelBuilderInstance($modelBuilderInstance):
      \CvResource::setModelBuilderInstance($modelBuilderInstance);
    return $this;
  }
  public function setModelCollectionInstance($modelCollectionInstance=null){
    $this->cvResource ?
      $this->cvResource->setModelCollectionInstance($modelCollectionInstance):
      \CvResource::setModelCollectionInstance($modelCollectionInstance);
    return $this;
  }
  public function setRequestClass($requestClass=null){
    $this->cvResource ?
      $this->cvResource->setRequestClass($requestClass):
      \CvResource::setRequestClass($this);
    return $this;
  }
  public function setRequestInstance($requestInstance=null){
    $this->cvResource ?
      $this->cvResource->setRequestInstance($requestInstance):
      \CvResource::setRequestInstance($requestInstance);
    return $this;
  }
  public function setUserModelClass($userModelClass=null){
    $this->cvResource ?
      $this->cvResource->setUserModelClass($userModelClass):
      \CvResource::setUserModelClass($this);
    return $this;
  }
  public function setUserModelBuilderInstance($userModelBuilderInstance=null){
    $this->cvResource ?
      $this->cvResource->setUserModelBuilderInstance($userModelBuilderInstance):
      \CvResource::setUserModelBuilderInstance($userModelBuilderInstance);
    return $this;
  }
  public function setUserModelCollectionInstance($userModelCollectionInstance=null){
    $this->cvResource ?
      $this->cvResource->setUserModelCollectionInstance($userModelCollectionInstance):
      \CvResource::setUserModelCollectionInstance($userModelCollectionInstance);
    return $this;
  }
  public function setPaginatorClass($paginatorClass=null){
    $this->cvResource ?
      $this->cvResource->setPaginatorClass($paginatorClass):
      \CvResource::setPaginatorClass($paginatorClass);
    return $this;
  }
  public function setPaginatorInstance($paginatorInstance=null){
    $this->cvResource ?
      $this->cvResource->setPaginatorInstance($paginatorInstance):
      \CvResource::setPaginatorInstance($paginatorInstance);
    return $this;
  }


  //-----------
  public function setRows($rows=null){
    $this->cvResource ?
      $this->cvResource->setRows($rows):
      \CvResource::setRows($rows);
    return $this;
  }
  public function setRow($row=null){
    $this->cvResource ?
      $this->cvResource->setRow($row):
      \CvResource::setRow($this);
    return $this;
  }
  public function setCurrentAction($currentAction=null){
    $this->cvResource ?
      $this->cvResource->setCurrentAction($currentAction):
      \CvResource::setCurrentAction($this);
    return $this;
  }
  public function setCurrentActionKey($currentActionKey=null){
    $this->cvResource ?
      $this->cvResource->setCurrentActionKey($currentActionKey):
      \CvResource::setCurrentActionKey($currentActionKey);
    return $this;
  }
  public function setFields($fields=null){
    $this->cvResource ?
      $this->cvResource->setFields($fields):
      \CvResource::setFields($this);
    return $this;
  }
  //consider to include and interface to ensure CvResource requiriments
  public function setCvResource($cvResource){
    $this->cvResource = $cvResource;
  }
  public function injectCvResource(){
    $this->cvResource = \CvResource::getCvResourceInstance();
    return $this;
  }
  //Setters end
//-----

  public function autoSetPropertys(...$propertyRewriter){
    if(!empty($propertyRewriter) && is_array($propertyRewriter))
      foreach ($propertyRewriter as $param)
        foreach ($param as $key => $value)
          if(property_exists ( $this , $key))
            $this->{$key} = $value;
  }
  public function modelInstanciator($new=false){
    $model = $this->modelSource = $this->modelSource?
      $this->modelSource:
      "App\Models\\".$this->getCrudObjectName();
    if(!class_exists($model))
      return null;
    if($new)
      return new $model();
    return $model::noFilters();
  }

  public function setModelInstance(){
    if(($this->model = $this->modelInstanciator())){
      $this->mainTableName = $this->model->getModel()->getTable().'.';
      if(!empty($this->currentActionId) && !empty($this->currentAction)){
        $this->modelInstance = $this->model->id($this->currentActionId)->first();
      }
    }
  }

  public function loadFields(){
    if($this->cvResource->getRequestInstance())
      $this->fields = $this->cvResource->getRequestInstance()->all();
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
    if( !$this->currentUser ||
      $this->cvResource->getUserCollectionInstance()->isRoot()
    )
      return true;

    if($this->cvResource->getUserCollectionInstance()->specialPermissions()->slug($this->cvResourceLangCase().".general-owner")->count())
      $this->model->generalOwner($this->cvResource->getUserCollectionInstance()->id);
    else
      if($this->cvResource->getUserCollectionInstance()->specialPermissions()->slug($this->cvResourceLangCase().".particular-owner")->count())
          $this->model->particularOwner($this->cvResource->getUserCollectionInstance()->id);

    if(!$this->currentActionId)
      return true;

    $this->model->id($this->currentActionId);

    return $this->model->count();
  }
}
