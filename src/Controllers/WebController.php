<?php

namespace Crudvel\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class WebController extends CustomController
{
	public $rowLabel;
	public $rowsLabel;
    public $singularSlug;
    public $pluralSlug;
	public $viewFolder;
	public $rowGender;
    public $selectColumns;
    public $actionResponse;
    public $menu;
    public $submenu;
    public $viewSharedPropertys = [
        "actionsLangs",
        "crudvel",
        "currentAction",
        "currentActionId",
        "langName",
        "menu",
        "pluralSlug",
        "prefix",
        "resource",
        "rowGender",
        "rowLabel",
        "rowName",
        "rowsLabel",
        "rowsName",
        "singularSlug",
        "submenu",
        "viewFolder",
    ];
    //nombre de la clase del controller actual

    public function __construct(...$propertyRewriter){
        parent::__construct(...$propertyRewriter);
    }

    public function  callAction($method, $parameters=[]){
        if(empty($this->rowLabel))
            $this->rowLabel = trans("crudvel/".$this->langName.".row_label");
        if(empty($this->rowsLabel))
            $this->rowsLabel = trans("crudvel/".$this->langName.".rows_label");
        if(empty($this->singularSlug))
            $this->singularSlug = str_slug($this->rowLabel);
        if(empty($this->pluralSlug))
            $this->pluralSlug = str_slug($this->rowsLabel);
        if(empty($this->viewFolder))
            $this->viewFolder = $this->langName;
        if(empty($this->resource))
            $this->resource = $this->viewFolder;

        $next = empty($this->model)?$this->redirectBackWithInput():parent::callAction($method,$parameters);
        if(!empty($this->currentUser))
            $this->viewSharedPropertys[]="currentUser";
        if(!empty($this->request->baseName)){
            if(empty($this->resource)){
                $this->resource = $this->request->baseName;
                //$this->baseResourceUrl =  (!empty($this->prefix)?$this->prefix."/":"").$this->resource;
            }
        }
        if(in_array($method,$this->viewActions))
            $this->globalViewShare();
        return $next;
    }

    public function globalViewShare(){
        foreach ($this->viewSharedPropertys as $property)
            if(isset($this->{$property}) && $this->{$property})
                View::share($property, $this->{$property});
    }

    public function rowLabelTrans(){
    	if($this->rowGender==="M")
    		return "El ".$this->rowLabel;
        if($this->rowGender==="F")
            return "La ".$this->rowLabel;
    	return $this->rowLabel;
    }

    public function rowsLabelTrans(){
    	if($this->rowGender==="M")
    		return "Los ".$this->rowsLabel;
        if($this->rowGender==="M")
    	   return "Las ".$this->rowsLabel;
       return $this->rowsLabel;

    }

    public function createEditAction($action){
        View::share("page_title", trans("crudvel.actions.".$action.".called_message")." ".$this->rowLabel);
        View::share("row",$this->model->first());
        return view("backend.layout.partials.actions.create-edit");
    }

    public function singleRowViewAction($action){
        View::share("page_title", trans("crudvel.actions.".$action.".called_message")." ".$this->rowLabel);
        View::share("row",$this->model->first());
        return view("backend.".$this->viewFolder.".".$action);
    }

    public function index(){
        View::share("page_title", 
            trans("crudvel.actions.".$this->currentAction.".called_message").
            " ".
            trans("crudvel.actions.common.of").
            " ".
            $this->rowsLabel);
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
            "redirector"=>Redirect::to($this->request->fullUrl())
        ]):$this->webFailResponse();
    }

    public function update($id){
        return $this->persist()?$this->webSuccessResponse():$this->webFailResponse();
    }

    public function destroy($id){
        return $this->model->delete()?$this->webSuccessResponse():$this->webFailResponse();
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
            trans("crudvel.actions.".$this->currentAction.".called_message").
            " ".
            $this->rowsLabel);
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
