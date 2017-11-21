<?php 
namespace Crudvel\Traits;

/*
    This is a customization of the controller, some controllers needs to save multiple data on different tables in one step, doing that without transaction is a bad idea.
    so the options is to doing it manually, but it is a lot of code, and it is always the same, to i get that code and put it togheter as methods, so now with the support of
    the anonymous functions, all this code can be reused, saving a lot of time.
*/
use Illuminate\Support\Facades\Session;
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
                ["status"=>trans('crudvel.api.already_exist')],
            409
        );
    }

    public function apiUnautorized($data=null){
        return response()->json($data?
            $data:
            ["status"=>trans('crudvel.api.unautorized')]
            ,403
        );
    }

    public function apiNotFound($data=null){
        return response()->json($data?
            $data:
            ["status"=>trans('crudvel.api.not_found')]
            ,404
        );
    }

    public function apiSuccessResponse($data=null){
        return response()->json($data?
            $data:
            ["status"=>trans('crudvel.api.success')]
            ,200
        );
    }

    public function apiFailResponse($data=null){
        return  response()->json($data?
            $data:
            ["status"=>trans('crudvel.api.transaction-error'),"error-message"=>trans('crudvel.api.operation_error')]
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
    public function autoResponder($responseProperty=[]){

        if(empty($responseProperty))
            return \Illuminate\Support\Facades\Redirect::back()->withInput($this->fields);
        Session::flash(
            $responseProperty["status"]?
                $responseProperty["status"]:
                "success",
                $responseProperty["statusMessage"]?
                    $responseProperty["statusMessage"]:
                    "Correcto"
        );
        Session::flash(
            "statusMessage",
            $responseProperty["statusMessage"]?
                $responseProperty["statusMessage"]:
                "Correcto"
            );
        if(isset($responseProperty["withInput"])){
            $redirector=(
                $responseProperty["redirector"]?
                    $responseProperty["redirector"]:
                    \Illuminate\Support\Facades\Redirect::back()
            );
            if($responseProperty["withInput"])
                $redirector->withInput(
                    !empty($responseProperty["inputs"])?
                        $responseProperty["inputs"]:
                        $this->fields
                );
        }
        else
            $redirector=$responseProperty["redirector"]?
                $responseProperty["redirector"]:
                \Illuminate\Support\Facades\Redirect::back();
            $redirector->withInput(
                    !empty($responseProperty["inputs"])?
                            $responseProperty["inputs"]:
                            $this->fields
                    );
        if(!empty($responseProperty["errors"]))
            Session::flash($errors, $responseProperty["errors"]);

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
    public function webUnauthorized($responseProperty=[]){
        $responseProperty["status"]=!empty($responseProperty["status"])?
            $responseProperty["status"]:
            "danger";
        $responseProperty["statusMessage"]=!empty($responseProperty["statusMessage"])?
            $responseProperty["statusMessage"]:
            trans('crudvel.web.unautorized');
        $responseProperty["redirector"]=!empty($responseProperty["redirector"])?
            $responseProperty["redirector"]:
            null;
        $responseProperty["errors"]=!empty($responseProperty["errors"])?
            $responseProperty["errors"]:[];
        return $this->autoResponder($responseProperty);
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
    public function webNotFound($responseProperty=[]){
        $responseProperty["status"]=!empty($responseProperty["status"])?
            $responseProperty["status"]:"warning";
        $responseProperty["statusMessage"]=!empty($responseProperty["statusMessage"])?
            $responseProperty["statusMessage"]:trans('crudvel.web.not_found');
        $responseProperty["redirector"]=!empty($responseProperty["redirector"])?
            $responseProperty["redirector"]:null;
        $responseProperty["errors"]=!empty($responseProperty["errors"])?
            $responseProperty["errors"]:[];
        return $this->autoResponder($responseProperty);
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
    public function webSuccessResponse($responseProperty=[]){
        $responseProperty["status"]=!empty($responseProperty["status"])?
            $responseProperty["status"]:"success";
        $responseProperty["statusMessage"]=!empty($responseProperty["statusMessage"])?
            $responseProperty["statusMessage"]:"Se ha ".trans("crudvel.actions.".$this->currentAction.".success")." ".$this->rowLabelTrans()." ".trans("crudvel.actions.common.correctly");
        $responseProperty["redirector"]=!empty($responseProperty["redirector"])?
            $responseProperty["redirector"]:null;
        $responseProperty["errors"]=!empty($responseProperty["errors"])?
            $responseProperty["errors"]:[];
        return $this->autoResponder($responseProperty);
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
    public function webFailResponse($responseProperty=[]){
        $responseProperty["status"]=!empty($responseProperty["status"])?
            $responseProperty["status"]:"danger";
        $responseProperty["statusMessage"]=!empty($responseProperty["statusMessage"])?
            $responseProperty["statusMessage"]:trans('crudvel.web.transaction-error');
        $responseProperty["redirector"]=!empty($responseProperty["redirector"])?
            $responseProperty["redirector"]:null;
        $responseProperty["errors"]=!empty($responseProperty["errors"])?
            $responseProperty["errors"]:[];
        return $this->autoResponder($responseProperty);
    }
}