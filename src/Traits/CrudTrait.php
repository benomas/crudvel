<?php 
namespace Crudvel\Traits;

/*
    This is a customization of the controller, some controllers needs to save multiple data on different tables in one step, doing that without transaction is a bad idea.
    so the options is to doing it manually, but it is a lot of code, and it is always the same, to i get that code and put it togheter as methods, so now with the support of
    the anonymous functions, all this code can be reused, saving a lot of time.
*/
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
trait CrudTrait {

    public function setEntity(){
        $this->crudObjectName = str_replace($this->getClassType(),"",$this->baseClass);
    }

    public function explodeClass(){
        if(empty($this->baseClass))
            $this->baseClass=class_basename(get_class($this));

        if(empty($this->classType)){
            foreach (["Controller","Request"] as $classType) 
                if($this->testClassType($classType))
                    $this->classType = $classType;
            if(empty($this->classType))
                $this->classType = "Model";
        }

        if(empty($this->crudObjectName))
            $this->crudObjectName = str_replace($this->classType,"",$this->baseClass);
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
            $this->rowName = camel_case($this->crudObjectName);
        return $this->rowName;
    }
    
    public function autoSetPropertys(...$propertyRewriter){
        if(!empty($propertyRewriter) && is_array($propertyRewriter))
            foreach ($propertyRewriter as $param)
                foreach ($param as $key => $value)
                    if(property_exists ( $this , $key))
                        $this->{$key} = $value;
    }

    public function setCurrentUser(){
        $user = $this->getClassType()==="Request"?
            $this->user():
            $this->request->user();
        $userModelSource = config("auth.providers.users.model","Crudvel\Models\User");
        $this->currentUser=$user?
            $userModelSource::id($user->id):
            null;
    }

    public function testClassType($tryClassType){
        return strstr($this->baseClass,$tryClassType)===$tryClassType;
    }

    public function loadFields(){
        $this->fields = $this->getClassType()==="Request"?
            $this->all():
            $this->request->all();
        if(!empty($this->defaultFields))
            foreach ($this->defaultFields as $field => $value)
                if(empty($this->fields[$field]))
                    $this->fields[$field]=$value;
    }

    public function apiAlreadyExist($data=null){
        return response()->json(
            $data?
                $data:
                ["status"=>trans("crudvel.api.already_exist")],
            409
        );
    }

    public function apiUnautorized($data=null){
        return response()->json($data?
            $data:
            ["status"=>trans("crudvel.api.unautorized")]
            ,403
        );
    }

    public function apiNotFound($data=null){
        return response()->json($data?
            $data:
            ["status"=>trans("crudvel.api.not_found")]
            ,404
        );
    }

    public function apiSuccessResponse($data=null){
        return response()->json($data?
            $data:
            ["status"=>trans("crudvel.api.success")]
            ,200
        );
    }

    public function apiFailResponse($data=null){
        return  response()->json($data?
            $data:
            ["status"=>trans("crudvel.api.transaction-error"),"error-message"=>trans("crudvel.api.operation_error")]
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
            $respProp["status"]?$respProp["status"]:"success",
            $respProp["statusMessage"]?$respProp["statusMessage"]:"Correcto"
        );
        Session::flash("statusMessage",$respProp["statusMessage"]?$respProp["statusMessage"]:"Correcto");
        if(isset($respProp["withInput"])){
            $redirector=($respProp["redirector"]?$respProp["redirector"]:Redirect::back());
            if($respProp["withInput"])
                $redirector->withInput(!empty($respProp["inputs"])?$respProp["inputs"]:$this->fields);
        }
        else
            $redirector=$respProp["redirector"]?$respProp["redirector"]:Redirect::back();
            $redirector->withInput(!empty($respProp["inputs"])?$respProp["inputs"]:$this->fields);
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
            return trans("crudvel.actions.".$this->currentAction.".success")." ".$label." ".trans("crudvel.actions.common.correctly");
        }
        return trans("crudvel.web.not_found");
    }
}