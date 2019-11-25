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
    $this->addActions("select","resourcePermissions");
  }
  public function callAction($method,$parameters=[]){
    $this->prepareResource();
    if(!$this->getCurrentAction())
      $this->setCurrentAction($method)->fixActionResource();

    if($this->skipModelValidation && !$this->getModelBuilderInstance())
      return $this->apiNotFound();

    if(!in_array($this->getCurrentAction(),$this->actions))
      return $this->apiNotFound();

    if(
      $this->skipModelValidation &&
      !specialAccess($this->setUserModelBuilderInstance(),"inactives") &&
      !specialAccess($this->setUserModelBuilderInstance(),$this->getSlugPluralName().'.inactives')
    )
      $this->getModelBuilderInstance()->actives();
    $this->loadFields();
    $preactionResponse = $this->preAction($method,$parameters);
    if($preactionResponse)
      return $preactionResponse;
    if(in_array($method,$this->rowActions)){
      if(empty($parameters))
        return $this->apiNotFound();
      $this->setCurrentActionKey($parameters[$this->getSnakeSingularName()]);
      if(!$this->getModelBuilderInstance()->key($this->getCurrentActionKey())->count())
        return $this->apiNotFound();
      $this->setModelCollectionInstance($this->getModelBuilderInstance()->first());
    }
    if(in_array($method,$this->rowsActions) && $this->getModelBuilderInstance()->count() === 0)
      return $this->apiSuccessResponse([
        "data"    => [],
        "count"   => 0,
        "message" => trans("crudvel.api.success")
      ]);
    return parent::callActionJump($method,$parameters);
  }
  //web routes
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
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store()
  {
    $this->setStamps();
    if($this->persist())
      return ($this->getPaginatorInstance()->getPaginable() && $this->currentPaginator->extractPaginate())?
        $this->currentPaginator->paginatedResponse():
        $this->currentPaginator->noPaginatedResponse();
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
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update($id)
  {
    $this->fields["id"] = $id;
    $this->setStamps();
    unset($this->fields["created_by"]);
    if($this->persist())
      return ($paginable = $this->paginable && $this->currentPaginator->extractPaginate())?
        $this->currentPaginator->paginatedResponse():
        $this->currentPaginator->noPaginatedResponse();
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


  public function activate($id){
    $this->fields["status"]=1;
    return $this->update($id);
  }

  public function deactivate($id){
    $this->fields["status"]=0;
    return $this->update($id);
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

  protected function setStamps(){
    //$rightNow = Carbon::now()->toDateTimeString();
    $this->fields["created_by"] = $this->getRequestInstance()->user()->key??null;
    $this->fields["updated_by"] = $this->getRequestInstance()->user()->key??null;
    //$this->fields["created_at"] = $rightNow??null;
    //$this->fields["updated_at"] = $rightNow??null;
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

  /**
   * This function allow to transform simple orderBy column to a table.column field, in this way
   * is posible to direct use joinable columns, the model needs to declare de join tables too, maybe * rewriting call_action function for global resource join, or by action for specific join
   *
   * @param simpleColumns   string of orderBy
   *
   * @author Benomas benomas@gmail.com
   * @date   2018-04-04
   * @return string with fixed orderBy column
   */
  public function fixOrderBy(){
    if(!empty($this->joinables[$this->orderBy]))
      $this->orderBy = $this->joinables[$this->orderBy];
    else
      $this->orderBy = $this->mainTableName.$this->orderBy;
  }

  /*
  protected function setPaginator(){
    $paginatorMode = $this->getRequestInstance()->get("paginate");
    $paginatorClass = $this->paginators[$paginatorMode['searchMode']??'cv-simple-paginator'];
    $this->currentPaginator = new $paginatorClass($this);
  }
  */
  //rewrite this method
  public function joins(){}

  //rewrite this method for custom logic
  public function unions(){
    $union = kageBunshinNoJutsu($this->getModelBuilderInstance());
    $union->select($this->getPaginatorInstance()->getSelectQuery());
    $union->union($this->getModelBuilderInstance()->select($this->getPaginatorInstance()->getSelectQuery()));
    $this->setModelBuilderInstance($union);
  }
}
