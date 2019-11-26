<?php namespace Crudvel\Controllers;
/**
 *
 *
 * @author Benomas benomas@gmail.com
 * @date   2017-05-08
 */

//Se extiende transactionController, para manejo de transacciones

class ApiController extends CustomController{

  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
    $this->addAction("select","resourcePermissions");
  }
  public function callAction($method,$parameters=[]){
    $this->prepareResource();
    if(!$this->getCurrentAction())
      $this->setCurrentAction($method)->fixActionResource();
    if($this->skipModelValidation && !$this->getModelBuilderInstance())
      return $this->apiNotFound();

    if(!in_array($this->getCurrentAction(),$this->getActions()))
      return $this->apiNotFound();

    if(
      $this->skipModelValidation &&
      !$this->specialAccess('inactives') &&
      !$this->specialAccess($this->getSlugPluralName().'.inactives')
    )
      $this->getModelBuilderInstance()->actives();
    if(!$this->getFields())
      $this->loadFields();
    $preactionResponse = $this->preAction($method,$parameters);
    if($preactionResponse)
      return $preactionResponse;
    if(in_array($method,$this->getRowActions())){
      if(empty($parameters))
        return $this->apiNotFound();
      $this->setCurrentActionKey($parameters[$this->getSnakeSingularName()]);
      if(!$this->getModelBuilderInstance()->key($this->getCurrentActionKey())->count())
        return $this->apiNotFound();
      $this->setModelCollectionInstance($this->getModelBuilderInstance()->first());
    }
    if(in_array($method,$this->getRowsActions()) && $this->getModelBuilderInstance()->count() === 0)
      return $this->apiSuccessResponse([
        "data"    => [],
        "count"   => 0,
        "message" => trans("crudvel.api.success")
      ]);
    return parent::callActionJump($method,$parameters);
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    if(
      $this->getRootInstance() &&
      $this->getRootInstance()->getPaginable() &&
      $this->getPaginatorInstance()->extractPaginate()
    )
      return $this->getPaginatorInstance()->paginatedResponse();
    return$this->apiSuccessResponse($this->getModelBuilderInstance()->get());
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
    return $this->index();
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
      return $this->show(1);
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
    if(
      $this->getRootInstance() &&
      $this->getRootInstance()->getPaginable() &&
      $this->getPaginatorInstance()->extractPaginate()
    )
      return $this->getPaginatorInstance()->paginatedResponse();
    return $this->getPaginatorInstance()->noPaginatedResponse();
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
      return $this->show($id);
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
      if(resourceAccess($this->currentUser,str_plural(Str::slug($this->crudObjectName)).".".Str::slug($action)))
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
