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
  use \Crudvel\Traits\ExportSpreadSheetTrait;

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

// [Actions]
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return $this->actionResponse();
  }

  /**
   * Display a listing of the resource related to another resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function relatedIndex($resource=null,$key=null) {
    try{
      if ($key === null || $key === 'null')
        return $this->apiSuccessResponse([]);

      if (method_exists($this->getModelBuilderInstance(),'relatedTo'.Str::studly(Str::singular($resource))))
        $this->getModelBuilderInstance()->{'relatedTo'.Str::studly(Str::singular($resource))}($key);
      else
        $this->getModelBuilderInstance()->relatedTo($resource,$key);

      return $this->actionResponse();
    }catch(\Exception $e) {
    }

    return $this->apiSuccessResponse([
      "data"    => [],
      "count"   => 0,
      "message" => trans("crudvel.api.success")
    ]);
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

    if($this->persist()){
      $this->getModelBuilderInstance()->key($this->getModelCollectionInstance()->getKeyValue());

      return $this->actionResponse();
    }

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
// [End Actions]

// [Methods]
  protected function getDataRequest(){
    $this->fields =  $this->getRequestInstance()->all();

    if(isset($this->forceNulls) && is_array($this->forceNulls)){
      foreach($this->forceNulls AS $forceNull){
        if(!isset($this->fields[$forceNull]))
          $this->fields[$forceNull]=null;
      }
    }
  }

  protected function fixPassword(){
    $fields = $this->getFields();
    if(empty($fields['password']))
      $this->removeField('password');
    else
      $this->addField('password',bcrypt($fields['password']));
    return $this;
  }

  public static function externalAttacher($modelCollectionInstance,$resource,$fields){
    $toAttach = cvGetSomeKeysAsList($fields[cvCaseFixer('plural|snake',$resource).'_attach']??[]);
    $modelCollectionInstance->{cvCaseFixer('plural|camel',$resource)}()->detach($toAttach);
    $modelCollectionInstance->{cvCaseFixer('plural|camel',$resource)}()->attach($toAttach);

    return $modelCollectionInstance;
  }

  protected function attacher($resource){
    $toAttach = cvGetSomeKeysAsList($this->getFields()[cvCaseFixer('plural|snake',$resource).'_attach']??[]);
    $this->getModelCollectionInstance()->{cvCaseFixer('plural|camel',$resource)}()->detach($toAttach);
    $this->getModelCollectionInstance()->{cvCaseFixer('plural|camel',$resource)}()->attach($toAttach);

    return $this;
  }

  protected function detacher($resource){
    $toDetach = cvGetSomeKeysAsList($this->getFields()[cvCaseFixer('plural|snake',$resource).'_detach']??[]);
    $this->getModelCollectionInstance()->{cvCaseFixer('plural|camel',$resource)}()->detach($toDetach);

    return $this;
  }

  public static function externalStoreRelation($modelCollectionInstance,$resource,$fields){
    return static::externalAttacher($modelCollectionInstance,$resource,$fields);
  }

  protected function storeRelation($resource){
    return $this->attacher($resource);
  }

  protected function updateRelation($resource){
    return $this->detacher($resource)->attacher($resource);
  }


  protected function dissociateResource($resource,$resourceKeys=[],$forceColumn = null){
    $resourceModel = '\App\Models\\'.cvCaseFixer('singular|studly',$resource);
    $resourceRows = $resourceModel::byResource($this->getSlugSingularName(),$this->getModelCollectionInstance()->id)
    ->noKeys($resourceKeys)->get();
    $forceColumn = $forceColumn ??  $this->getSnakeSingularName().'_id';
    foreach($resourceRows as $resource)
      if(!$resource->fill([$forceColumn=>null])->save())
        return false;

    return true;
  }

  public static function externalAssociateResource($keyValueResource,$relatedResource,$relatedResourceKeys=[]){
    $relatedResourceModel = '\App\Models\\'.cvCaseFixer('singular|studly',$relatedResource);

    foreach($relatedResourceModel::keys($relatedResourceKeys)->get() as $currentResource)
      if(!$currentResource->fill($keyValueResource)->save())
        return false;

    return true;
  }

  protected function associateResource($resource,$resourceKeys=[],$forceColumn = null){
    $resourceModel = '\App\Models\\'.cvCaseFixer('singular|studly',$resource);
    $forceColumn = $forceColumn ??  $this->getSnakeSingularName().'_id';
    foreach($resourceModel::keys($resourceKeys)->get() as $resource)
      if(!$resource->fill([$forceColumn=>$this->getModelCollectionInstance()->id])->save())
        return false;

    return true;
  }

  public static function externalStoreAssociated($keyValueResource,$relatedResource,$relatedResourceKeys=[]){
    return static::externalAssociateResource($keyValueResource,$relatedResource,$relatedResourceKeys);
  }

  protected function storeAssociated($resource,$resourceKeys=[],$forceColumn = null){
    return $this->associateResource($resource,$resourceKeys,$forceColumn);
  }

  protected function updateAssociated($resource,$resourceKeys=[],$forceColumn = null){
    return $this->dissociateResource($resource,$resourceKeys,$forceColumn) &&  $this->associateResource($resource,$resourceKeys,$forceColumn);
  }

  public function blackListExportingColumns(){
    return [];
  }

  public function whiteListExportingColumns(){
    return [];
  }
// [End Methods]
}
