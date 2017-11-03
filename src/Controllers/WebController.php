<?php

namespace Crudvel\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class WebController extends CustomController
{
	public $singularViewLabel;
	public $pluralViewLabel;
	public $objectViewFolder;
    public $objectViewVariable;
	public $genderViewLabel;
    public $storeAction;
    public $updateAction;
    public $deleteAction;
    public $selectColumns;
    public $actionResponse;
    //nombre de la clase del controller actual

    public function __construct(...$propertyRewriter){
        parent::__construct(...$propertyRewriter);
        $this->preConstructor();
        $this->storeAction    = "store";
        $this->updateAction   = "update";
        $this->deleteAction   = "destroy";
        $this->storeLabel     = "registrar";
        $this->updateLabel    = "editar";
        $this->deleteLabel    = "eliminar";
        $this->globalViewShare();
    }

    public function  callAction($method, $parameters=[]){
        $next = empty($this->modelInstance)?$this->redirectBackWithInput():parent::callAction($method,$parameters);
        if(!empty($this->currentUser))
            View::share("currentUser", $this->currentUser);
        if(!empty($this->request->baseName)){
            if(empty($this->resursePrefix)){
                $this->resursePrefix = $this->request->baseName;
                View::share("resursePrefix", $this->resursePrefix);
            }
        }
        return $next;
    }

    public function preConstructor(){}

    public function globalViewShare(){
        $properties=[
            "singularViewLabel",
            "pluralViewLabel",
            "genderViewLabel",
            "resursePrefix",
            "objectViewFolder",
            "objectPluralViewVariable",
            "objectSingularViewVariable",
            "storeAction",
            "updateAction",
            "deleteAction",
            "storeLabel",
            "updateLabel",
            "deleteLabel",
        ];

        foreach ($properties as $property)
            if(isset($this->{$property}) && $this->{$property})
                View::share($property, $this->{$property});
        View::share("prefix", env("PREFIX", ""));
    }

    public function failRequirements($message,$redirect){
        Session::flash("error", $message);
        if(is_callable($redirect))
            return $redirect();
        if(is_callable([$this,$redirect]))
            return $this->{$redirect}();

        return Redirect::to($redirect)->withInput();
    }

    public function success($message,$redirect){
        Session::flash("success", $message);
        if(is_callable($redirect))
            return $redirect();
        if(is_callable([$this,$redirect]))
            return $this->{$redirect}();

        return Redirect::to($redirect);
    }

    public function redirectBack(){
        return Redirect::back();
    }

    public function redirectBackWithInput(){
        $allInputs = $this->request->all();
        $allInputs["lastAction"] = $this->currentAction;
        if($this->currentAction ==="update")
            $allInputs["lastActionId"] = $this->request->route('id');
        return Redirect::back()->withInput($allInputs);
    }

    public function translateAction($result=false){
    	switch($this->currentAction){
    		case "store":
    			if($result==='success')
    				return "Creado";
				return "Crear";
    		case "update":
    			if($result==='success')
    				return "Editado";
    			return "Editar";
    		case "destroy":
    			if($result==='success')
    				return "Eliminado";
    			return "Eliminar";
    	}

    	return "";
    }

    public function failOperation($message=null){
        return $this->failRequirements(
            $message?
                $message:
                "No se ha podido <b>".$this->translateAction()."</b> ".$this->singularViewLabel().", intente nuevamente.","redirectBackWithInput");
    }

    public function successOperation($message=null){
        return $this->success(
            $message?
            $message:
            "Se ha <b>".$this->translateAction("success")."</b> ".$this->singularViewLabel()." correctamente.","redirectBack");
    }

    public function singularViewLabel(){
    	if($this->genderViewLabel==="M")
    		return "El ".$this->singularViewLabel;
        if($this->genderViewLabel==="F")
            return "La ".$this->singularViewLabel;
    	return $this->singularViewLabel;
    }

    public function pluralViewLabel(){
    	if($this->genderViewLabel==="M")
    		return "Los ".$this->pluralViewLabel;
        if($this->genderViewLabel==="M")
    	   return "Las ".$this->pluralViewLabel;
       return $this->pluralViewLabel;

    }

    public function index(){
        View::share("page_title", "Listado de ".$this->pluralViewLabel);
		View::share($this->objectPluralViewVariable, $this->modelInstance->get());
        return view("backend.".$this->objectViewFolder.".index");
    }

    public function store(){
        return $this->persist()?$this->successOperation():$this->failOperation();
    }

    public function view($id){
        $this->modelInstance->id($id);
    	if( ( $currentModel = $this->modelInstance->first() ) ){
   			View::share($this->objectSingularViewVariable, $currentModel);
   			return $currentModel;
    	}
        $this->failRequirements($this->singularViewLabel().' no existe en la base de datos.','redirectBack');
    }

    public function update($id){
        return $this->persist()?$this->successOperation():$this->failOperation();
    }

    public function destroy($id){
        return !$this->modelInstance->delete()?$this->failOperation():$this->successOperation();
    }

    //to depreciate
    public function formatSelectParams($selects =[],$default='',$currents=''){
        return [
            "selects"=>$selects,
            "default"=>$default,
            "currents"=>!is_array($currents) && $currents?[$currents]:$currents,
        ];
    }

    public function apiIndex(){
        return response()->json(['data'=>$this->modelInstance->get()],200);
    }
}
