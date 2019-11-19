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
  public function getControllerClass(){
    if($this->cvResource)
      return $this->cvResource->getControllerClass();
    return \CvResource::getControllerClass();
  }
  public function getControllerInstance(){
    if($this->cvResource)
      return $this->cvResource->getControllerInstance();
    return \CvResource::getControllerInstance();
  }
  public function getModelClass(){
    if($this->cvResource)
      return $this->cvResource->getModelClass();
    return \CvResource::getModelClass();
  }
  public function getModelBuilderInstance(){
    if($this->cvResource)
      return $this->cvResource->getModelBuilderInstance();
    return \CvResource::getModelBuilderInstance();
  }
  public function getModelCollectionInstance(){
    if($this->cvResource)
      return $this->cvResource->getModelCollectionInstance();
    return \CvResource::getModelCollectionInstance();
  }
  public function getRequestClass(){
    if($this->cvResource)
      return $this->cvResource->getRequestClass();
    return \CvResource::getRequestClass();
  }
  public function getRequestInstance(){
    if($this->cvResource)
      return $this->cvResource->getRequestInstance();
    return \CvResource::getRequestInstance();
  }
  public function getSlugPluralName(){
    return $this->slugPluralName??null;
  }
  public function getSlugSingularName(){
    return $this->slugSingularName??\Illuminate\Support\Str::snake(str_replace('Controller','',class_basename($this)),'-');
  }
  //Getters end

  //Setters start
  public function setControllerClass($controllerClass){
    if($this->cvResource)
      return $this->cvResource->setControllerClass($controllerClass);
    return \CvResource::setControllerClass($controllerClass);
  }
  public function setControllerInstance($controllerInstance){
    if($this->cvResource)
      return $this->cvResource->setControllerInstance($controllerInstance);
    return \CvResource::setControllerInstance($controllerInstance);
  }
  public function setModelClass($modelClass){
    if($this->cvResource)
      return $this->cvResource->setModelClass($modelClass);
    return \CvResource::setModelClass($modelClass);
  }
  public function setModelBuilderInstance($modelBuilderInstance){
    if($this->cvResource)
      return $this->cvResource->setModelBuilderInstance($modelBuilderInstance);
    return \CvResource::setModelBuilderInstance($modelBuilderInstance);
  }
  public function setModelCollectionInstance($modelCollectionInstance){
    if($this->cvResource)
      return $this->cvResource->setModelCollectionInstance($modelCollectionInstance);
    return \CvResource::setModelCollectionInstance($modelCollectionInstance);
  }
  public function setRequestClass($requestClass){
    if($this->cvResource)
      return $this->cvResource->setRequestClass($requestClass);
    return \CvResource::setRequestClass($requestClass);
  }
  public function setRequestInstance($requestInstance){
    if($this->cvResource)
      return $this->cvResource->setRequestInstance($requestInstance);
    return \CvResource::setRequestInstance($requestInstance);
  }
  //consider to include and interface to ensure CvResource requiriments
  public function setCvResource($cvResource){
    $this->cvResource = $cvResource;
  }
  public function injectCvResource(){
    $this->cvResource = \CvResource::getCvResourceInstance();
    return $this->cvResource;
  }
  //Setters end
//-----
/*
  public function setEntity(){
    $this->crudObjectName = str_replace($this->getClassType(),'',$this->baseClass);
  }

  public function explodeClass(){
    $this->baseClass=$this->baseClass ?? class_basename(get_class($this));

    if(empty($this->crudObjectName))
      $this->crudObjectName = str_replace($this->classType,'',$this->baseClass);
  }

  public function getClassType(){
    if(empty($this->classType))
      $this->explodeClass();
    return $this->classType;
  }

  public function getBaseClass(){
    if(empty($this->baseClass))
      $this->explodeClass();
    return $this->baseClass;
  }

  public function getCrudObjectName(){
    if(empty($this->crudObjectName))
      $this->explodeClass();
    return $this->crudObjectName;
  }

  public function mainArgumentName(){
    if(empty($this->crudObjectName))
      $this->explodeClass();
    if(empty($this->rowName))
      $this->rowName = snake_case($this->crudObjectName);
    return snake_case($this->rowName);
  }
  public function setCurrentUser(){
    $user = $this->cvResource->getRequestInstance()->user();
    $userModelSource = "\App\Models\User";
    $user = $this->getClassType()==="Request"?
      $this->user():
      $this->request->user();
    $this->userModel=$user?
      $userModelSource::id($user->id):
      null;
    $this->currentUser = $this->userModel?$this->userModel->first():null;
  }
*/

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

  public function userInstance(){
    return $this->userModel->first();
  }

  public function testClassType($tryClassType){
    return strstr($this->baseClass,$tryClassType)===$tryClassType;
  }

  public function loadFields(){
    if($this->cvResource->getRequestInstance())
      $this->fields = $this->cvResource->getRequestInstance()->all();
    /*
    if($this->getClassType()==="Request")
      $this->fields = $this->all();
    else{
      $this->fields = empty($this->request->fields)?
        $this->request->all():$this->request->fields;
    }*/
    /*
    if(!empty($this->defaultFields))
      foreach ($this->defaultFields as $field => $value)
        if(empty($this->fields[$field]))
          $this->fields[$field]=$value;
    */
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
      $this->currentUser->isRoot()
    )
      return true;

    if($this->currentUser->specialPermissions()->slug($this->cvResourceLangCase().".general-owner")->count())
      $this->model->generalOwner($this->currentUser->id);
    else
      if($this->currentUser->specialPermissions()->slug($this->cvResourceLangCase().".particular-owner")->count())
          $this->model->particularOwner($this->currentUser->id);

    if(!$this->currentActionId)
      return true;

    $this->model->id($this->currentActionId);

    return $this->model->count();
  }
}
