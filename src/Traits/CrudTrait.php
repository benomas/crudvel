<?php 
namespace Crudvel\Traits;

/*
    This is a customization of the controller, some controllers needs to save multiple data on different tables in one step, doing that without transaction is a bad idea.
    so the options is to doing it manually, but it is a lot of code, and it is always the same, to i get that code and put it togheter as methods, so now with the support of
    the anonymous functions, all this code can be reused, saving a lot of time.
*/
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Crudvel\Models\User;
trait CrudTrait {

    public function setEntityName(){
        if(!empty($this->crudObjectName))
            return false;
        
        $classType = $this->getClassType();
        $entitySegments = [];
        preg_match("/(.*)?\\\(.*)?".$classType."$/",(get_class($this)),$entitySegments);
        
        if(!empty($entitySegments[2])){
            $this->crudObjectName = $entitySegments[2];
        }
        else{
            $entitySegments=[];
            preg_match("/(.*)?".$classType."$/",(get_class($this)),$entitySegments);
            $this->crudObjectName = $entitySegments[1];
        }
    }

    public function mainArgumentName(){
        if(empty($this->crudObjectName))
            $this->setEntityName();
        if(!empty($this->rowName))
            return $this->rowName;

        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->crudObjectName));
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
        $this->currentUser=$user?
            User::id($user->id):
            null;
    }

    public function getClassType(){
        return strstr(get_class($this),"Controller")?"Controller":"Request";
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