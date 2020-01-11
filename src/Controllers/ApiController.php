<?php namespace Crudvel\Controllers;
/**
 *
 *
 * @author Benomas benomas@gmail.com
 * @date   2017-05-08
 */

use Illuminate\Support\Str;
//Se extiende transactionController, para manejo de transacciones

class ApiController extends CustomController{

  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
  }
  public function callAction($method,$parameters=[]){
    $this->setCallActionMethod($method)
      ->setCallActionParameters($parameters)
      ->prepareResource();
    return $this->getFlowControl() ? $this->getFlowControl()():parent::callActionJump($method,$parameters);
  }

  public function actionResponse(){
    return $this->getPaginated()?
      $this->getPaginatorInstance()->paginatedResponse():
      $this->apiSuccessResponse($this->getModelBuilderInstance()->get());
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return $this->actionResponse();
  }

  //web routes
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function sluged()
  {
    if($this->setSlugField())
      $this->slugedResponse=true;
    return $this->actionResponse();
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    die();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function store()
  {
    $this->setStamps();
    if($this->persist())
      return $this->actionResponse();
    return $this->apiFailResponse();
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return $this->actionResponse();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    die();
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update($id)
  {
    $this->addField('id',$id);
    $this->setStamps();
    $this->removeField('created_by');
    if($this->persist())
      return $this->actionResponse();
    return $this->apiFailResponse();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    return $this->getModelBuilderInstance()->first()->delete()?
        $this->apiSuccessResponse():
        $this->apiFailResponse();
  }

  public function permissions(){
    $actionPermittions=[];
    foreach($this->actions AS $action){
      if(actionAccess($this->currentUser,Str::plural(Str::slug($this->crudObjectName)).".".Str::slug($action)))
        $actionPermittions[$action]=true;
      else
        $actionPermittions[$action]=false;
    }
    return $this->apiSuccessResponse($actionPermittions);
  }

  protected function getDataRequest(){
    $this->fields =  $this->getRequestInstance()->all();

    if(isset($this->forceNulls) && is_array($this->forceNulls)){
      foreach($this->forceNulls AS $forceNull){
        if(!isset($this->fields[$forceNull]))
          $this->fields[$forceNull]=null;
      }
    }
  }
}
