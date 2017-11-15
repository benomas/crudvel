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
                //$this->baseResourceUrl =  (!empty($this->prefix)?$this->prefix."/":"").$this->resource;
            }
        }
        if(in_array($method,$this->loadViewActions))
            $this->globalViewShare();
        return $next;
    }

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

    public function createEditAction($action){
        View::share("page_title", trans("crud.actions.".$action.".called_message")." ".$this->singularLabel);
        View::share("row",$this->model->first());
        return view("backend.layout.partials.actions.create-edit");
    }

    public function singleRowViewAction($action){
        View::share("page_title", trans("crud.actions.".$action.".called_message")." ".$this->singularLabel);
        View::share("row",$this->model->first());
        return view("backend.".$this->viewFolder.".".$action);
    }

    public function index(){
        View::share("page_title", 
            trans("crud.actions.".$this->currentAction.".called_message").
            " ".
            trans("crud.actions.common.of").
            " ".
            $this->pluralLabel);
		View::share("rows", $this->model->get());
        return view("backend.".$this->viewFolder.".".$this->currentAction);
    }

    public function show($id){
        return $this->singleRowViewAction(__FUNCTION__);
    }
    
    public function create(){
        $this->model->nullFilter();
        View::share("method","post");
        return $this->createEditAction(__FUNCTION__);
    }

    public function edit($id){
        View::share("method","put" );
        return $this->createEditAction(__FUNCTION__);
    }

    public function store(){
        $this->modelInstance = $this->modelInstanciator(true);
        return $this->persist()?$this->webSuccessResponse([
            "redirector"=>Redirect::to($this->prefix."/".$this->resource)
        ]):$this->webFailResponse();
    }

    public function update($id){
        return $this->persist()?$this->webSuccessResponse():$this->webFailResponse();
    }

    public function destroy($id){
        return !$this->model->delete()?$this->webSuccessResponse():$this->webFailResponse();
    }

    public function active($id){
        $this->fields["status"]=1;
        return $this->update($id);
    }

    public function deactive($id){
        $this->fields["status"]=0;
        return $this->update($id);
    }

    public function import(){
        View::share("page_title", 
            trans("crud.actions.".$this->currentAction.".called_message").
            " ".
            $this->pluralLabel);
        View::share("method","post");
        return view("backend.layout.partials.actions.".$this->currentAction);
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

    public function failRequirements($message,$redirect){
        Session::flash("error", $message);
        if(is_callable($redirect))
            return $redirect();
        if(is_callable([$this,$redirect]))
            return $this->{$redirect}();

        return Redirect::to($redirect)->withInput();
    }

}
