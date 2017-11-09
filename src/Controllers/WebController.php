<?php

namespace Crudvel\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class WebController extends CustomController
{
	public $singularLabel;
	public $pluralLabel;
    public $singularSlug;
    public $pluralSlug;
	public $viewFolder;
	public $genderLabel;
    public $selectColumns;
    public $actionResponse;
    public $menu;
    public $submenu;
    public $viewSharedPropertys = [
        "actionsLangs",
        "crudvel",
        "currentAction",
        "currentActionId",
        "genderLabel",
        "pluralLabel",
        "pluralSlug",
        "prefix",
        "resource",
        "rowName",
        "rowsName",
        "singularLabel",
        "singularSlug",
        "viewFolder",
        "menu",
        "submenu",
    ];
    //nombre de la clase del controller actual

    public function __construct(...$propertyRewriter){
        parent::__construct(...$propertyRewriter);
    }

    public function  callAction($method, $parameters=[]){
        $next = empty($this->model)?$this->redirectBackWithInput():parent::callAction($method,$parameters);
        if(!empty($this->currentUser))
            $this->viewSharedPropertys[]="currentUser";
        if(!empty($this->request->baseName)){
            if(empty($this->resource)){
                $this->resource = $this->request->baseName;
                $this->baseResourceUrl =  (!empty($this->prefix)?$this->prefix."/":"").$this->resource;
            }
        }
        if(in_array($method,$this->loadViewActions))
            $this->globalViewShare();
        return $next;
    }

    public function preConstructor(){}

    public function globalViewShare(){
        if(empty($this->singularSlug))
            $this->singularSlug = str_slug($this->singularLabel);
        if(empty($this->pluralSlug))
            $this->pluralSlug = str_slug($this->pluralLabel);
        if(empty($this->viewFolder))
            $this->viewFolder = $this->pluralSlug;
        if(empty($this->resource))
            $this->resource = $this->viewFolder;

        foreach ($this->viewSharedPropertys as $property)
            if(isset($this->{$property}) && $this->{$property})
                View::share($property, $this->{$property});
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
                "No se ha podido <b>".$this->translateAction()."</b> ".$this->singularLabel().", intente nuevamente.","redirectBackWithInput");
    }

    public function successOperation($message=null){
        return $this->success(
            $message?
            $message:
            "Se ha <b>".$this->translateAction("success")."</b> ".$this->singularLabel()." correctamente.","redirectBack");
    }

    public function singularLabel(){
    	if($this->genderLabel==="M")
    		return "El ".$this->singularLabel;
        if($this->genderLabel==="F")
            return "La ".$this->singularLabel;
    	return $this->singularLabel;
    }

    public function pluralLabel(){
    	if($this->genderLabel==="M")
    		return "Los ".$this->pluralLabel;
        if($this->genderLabel==="M")
    	   return "Las ".$this->pluralLabel;
       return $this->pluralLabel;

    }

    public function singleRowViewAction($action){
        View::share("page_title", trans("crud.actions.".$action.".called_message")." ".$this->singularLabel);
        View::share("row",$this->model->first() );
        return view("backend.".$this->viewFolder.".".$action);
    }

    public function index(){
        View::share("page_title", 
            trans("crud.actions.index.called_message").
            " ".
            trans("crud.actions.common.of").
            " ".
            $this->pluralLabel);
		View::share("rows", $this->model->get());
        return view("backend.".$this->viewFolder.".index");
    }

    public function show($id){
        return $this->singleRowViewAction(__FUNCTION__);
    }
    
    public function create(){
        View::share("method","post" );
        return $this->singleRowViewAction(__FUNCTION__);
    }

    public function edit($id){
        View::share("method","put" );
        return $this->singleRowViewAction(__FUNCTION__);
    }

    public function store(){
        return $this->persist()?$this->successOperation():$this->failOperation();
    }

    public function update($id){
        return $this->persist()?$this->successOperation():$this->failOperation();
    }

    public function destroy($id){
        return !$this->model->delete()?$this->failOperation():$this->successOperation();
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
        return response()->json(['data'=>$this->model->get()],200);
    }
}
