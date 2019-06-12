<?php namespace Crudvel\Controllers;
/**
 *
 *
 * @author Benomas benomas@gmail.com
 * @date   2017-05-08
 */

//Se extiende transactionController, para manejo de transacciones
use Carbon\Carbon;

class ApiController extends CustomController
{
  //arreglo de columnas que deben exisistir almenos como null, se requiere para procedimientos
  protected $forceSingleItemPagination = false;
  //arreglo con columnas que se permiten filtrar en paginado, si se setea con false, no se permitira filtrado, si se setea con null o no se setea, no se pondran restricciones en filtrado
  protected $filterables = null;
  //arreglo con columnas que se permiten ordenar en paginado, si se setea con false, no se permitira ordenar, si se setea con null o no se setea, no se pondran restricciones en ordenar
  protected $orderables = null;
  //boleado que indica si el controller permite paginacion de forma general
  protected $paginable = true;
  //boleado que indica si el controller permite paginacion de forma general
  protected $flexPaginable = true;
  //boleado que indica si el controller permite paginacion de forma general
  protected $badPaginablePetition = false;
  //arreglo con columnas que se permiten seleccionar en paginado, si se setea con false, no se permitira seleccionar de forma especifica, si se setea con null o no se setea, no se pondran restricciones en seleccionar de forma especifica
  protected $selectables = null;
  //mapa de columnas join,
  protected $joinables = null;
  protected $paginators = [
    'cv-simple-paginator'      => \Crudvel\Libraries\Paginators\CvSimplePaginator::class,
    'cv-combinatory-paginator' => \Crudvel\Libraries\Paginators\CvCombinatoryPaginator::class
  ];
  protected $currentPaginator = null;
  protected $comparator    = 'like';

  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
    $this->addActions("select","resourcePermissions");
  }

  public function resourcesExplode(){
    if(empty($this->langName))
      $this->langName = snake_case(str_plural($this->getCrudObjectName()));
    if(empty($this->rowLabel))
      $this->rowLabel = trans("crudvel/".$this->langName.".row_label");
    if(empty($this->rowsLabel))
      $this->rowsLabel = trans("crudvel/".$this->langName.".rows_label");
    if(empty($this->singularSlug))
      $this->singularSlug = str_slug($this->rowLabel);
    if(empty($this->pluralSlug))
      $this->pluralSlug = str_slug($this->rowsLabel);
    if(empty($this->rowName))
      $this->rowName = snake_case($this->getCrudObjectName());
  }

  public function callAction($method,$parameters=[]){
    $this->currentAction  = $method;
    $this->setRequestInstance();
    $this->model         = $this->request->model;
    if($this->skipModelValidation && empty($this->model))
      return $this->apiNotFound();

    $this->mainTableName = $this->request->mainTableName;

    if(!in_array($this->currentAction,$this->actions))
      return $this->apiNotFound();

    $this->setCurrentUser();
    $this->setLangName();
    if(
      $this->skipModelValidation &&
      !specialAccess($this->userModel,"inactives") &&
      !specialAccess($this->userModel,$this->request->baseName.'.inactives')
    )
      $this->model->actives();
    $this->loadFields();
    $preactionResponse = $this->preAction($method,$parameters);
    if($preactionResponse)
      return $preactionResponse;
    if(in_array($method,$this->rowActions)){
      if(empty($parameters))
        return $this->apiNotFound();
      $this->currentActionId=$parameters[$this->mainArgumentName()];
      if(!$this->model->id($this->currentActionId)->count())
        return $this->apiNotFound();
      $this->modelInstance =  $this->model->first();
    }
    $this->setPaginator();
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
    return ($this->paginable && $this->currentPaginator->extractPaginate())?
      $this->currentPaginator->paginatedResponse():
      $this->apiSuccessResponse($this->model->get());
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
      return ($this->paginable && $this->currentPaginator->extractPaginate())?
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
    return ($this->paginable && $this->currentPaginator->extractPaginate())?
      $this->currentPaginator->paginatedResponse():
      $this->currentPaginator->noPaginatedResponse();
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
    return $this->model->first()->delete()?
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
      if(resourceAccess($this->currentUser,str_plural(str_slug($this->crudObjectName)).".".str_slug($action)))
        $actionPermittions[$action]=true;
      else
        $actionPermittions[$action]=false;
    }
    return $this->apiSuccessResponse($actionPermittions);
  }

  protected function setStamps(){
    //$rightNow = Carbon::now()->toDateTimeString();
    $this->fields["created_by"] = $this->request->user()->id??null;
    $this->fields["updated_by"] = $this->request->user()->id??null;
    //$this->fields["created_at"] = $rightNow??null;
    //$this->fields["updated_at"] = $rightNow??null;
  }

  protected function getDataRequest(){
    $this->fields =  $this->request->all();

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

  protected function setPaginator(){
    $paginatorMode = $this->request->get("paginate");
    $paginatorClass = $this->paginators[$paginatorMode['searchMode']??'cv-simple-paginator'];
    $this->currentPaginator = new $paginatorClass($this);
  }

  public function getForceSingleItemPagination(){
    return $this->forceSingleItemPagination??null;
  }
  public function getFilterables(){
    return $this->filterables??null;
  }
  public function getOrderables(){
    return $this->orderables??null;
  }
  public function getPaginable(){
    return $this->paginable??null;
  }
  public function getFlexPaginable(){
    return $this->flexPaginable??null;
  }
  public function getBadPaginablePetition(){
    return $this->badPaginablePetitionx??null;
  }
  public function getSelectables(){
    return $this->selectables??null;
  }
  public function getJoinables(){
    return $this->joinables??null;
  }
  public function getPaginateData(){
    return $this->paginateData??null;
  }
  public function getRequest(){
    return $this->request??null;
  }
  //rewrite this method
  public function joins(){}

  //rewrite this method for custom logic
  public function unions(){
    $union = kageBunshinNoJutsu($this->model);
    $union->select($this->currentPaginator->getSelectQuery());
    $union->union($this->model->select($this->currentPaginator->getSelectQuery()));
    $this->model=$union;
  }
}
