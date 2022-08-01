<?php namespace Crudvel\Controllers;
/**
 *
 *
 * @author Benomas benomas@gmail.com
 * @date   2017-05-08
 */

use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
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
    $this->getModelBuilderInstance()->solveSearches();

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

      if (method_exists($this->getModelBuilderInstance(),'relatedTo'.cvCaseFixer('stydly|singular',$resource)))
        $this->getModelBuilderInstance()->{'relatedTo'.cvCaseFixer('stydly|singular',$resource)}($key);
      else
        $this->getModelBuilderInstance()->relatedTo($resource,$key);

      if(!$this->getModelBuilderInstance()->count())
        return $this->apiSuccessResponse([]);

      return $this->actionResponse();
    }catch(\Exception $e) {
      pdd($e->getMessage());
    }

    return $this->apiSuccessResponse([
      "data"    => [],
      "count"   => 0,
      "message" => trans("crudvel.api.success")
    ]);
  }

  /**
   * Display a listing of the resource related to another resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function indexOwnedBy($resource=null,$key=null) {
    try{
      if ($key === null || $key === 'null')
        return $this->apiSuccessResponse([]);

      if (method_exists($this->getModelBuilderInstance(),'ownedBy'.cvCaseFixer('stydly|singular',$resource)))
        $this->getModelBuilderInstance()->{'ownedBy'.cvCaseFixer('stydly|singular',$resource)}($key);
      else
        $this->getModelBuilderInstance()->ownedBy($resource,$key);

      if(!$this->getModelBuilderInstance()->count())
        return $this->apiSuccessResponse([]);

      return $this->actionResponse();
    }catch(\Exception $e) {
      pdd($e->getMessage());
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
      if($this->actionAccess(Str::plural(Str::slug($this->crudObjectName)).'.'.Str::slug($action)))
        $actionPermittions[$action]=true;
      else
        $actionPermittions[$action]=false;
    }
    return $this->apiSuccessResponse($actionPermittions);
  }

  public function importing(){
    Excel::import(new \Crudvel\Imports\BaseImport, $this->getRequestInstance()->toImport());

    return $this->apiSuccessResponse([]);
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

  public function blackListExportingColumns(){
    return [];
  }

  public function whiteListExportingColumns(){
    return [];
  }

  public static function loadAssociator(){
    return new \Crudvel\Libraries\CvResourceInteractions\CvAssociator;
  }

  public static function loadRelationator(){
    return new \Crudvel\Libraries\CvResourceInteractions\CvRelationator;
  }

  public static function loadSimpleRelationator(){
    return new \Crudvel\Libraries\CvResourceInteractions\CvSimpleRelationator;
  }

  public function relatedIndexBeforePaginate($params = []){
    extract($params);
    $this->getRootInstance()->addSelectables('related_order');
  }

  public function indexOwnedByBeforePaginate($params = []){
    extract($params);
    $this->getRootInstance()->addSelectables('related_order');
  }

  public function userStampBuilder ($foreinKey = 'created_by') {
    $userCvSearch = \App\Models\User::sSafeField('u','cv_search');
    $cTable = $this->getModelClass()::cvIam()->getTable();
    $cTable .= $this->getModelClass()::cvIam()->alias();

    return $this->selfPreBuilder($cTable)
      ->leftJoinSub(\App\Models\User::select('*')->disableRestricction()->cvSearch(), 'u',function ($join) use($cTable,$foreinKey){
        $join->on('u.id', '=', "{$cTable}.{$foreinKey}");
      })->selectRaw($userCvSearch);
  }

  public function addedCreatedByUser(){
    return $this->userStampBuilder('created_by');
  }

  public function addedUpdatedByUser(){
    return $this->userStampBuilder('updated_by');
  }
//to be deprecated
/*
// atachers
  public static function externalAttacher($modelCollectionInstance,$resource,$fields){
    $toAttach = cvGetSomeKeysAsList($fields[cvCaseFixer('plural|snake',$resource).'_attach']??[]);
    $modelCollectionInstance->{cvCaseFixer('plural|camel',$resource)}()->detach($toAttach);
    $modelCollectionInstance->{cvCaseFixer('plural|camel',$resource)}()->attach($toAttach);

    return $modelCollectionInstance;
  }

  protected function attacher($resource){
    static::externalAttacher($this->getModelCollectionInstance(),$resource,$this->getFields());

    return $this;
  }
// detachers
  protected function externalDetacher($modelCollectionInstance,$resource,$fields){
    $toDetach = cvGetSomeKeysAsList($fields[cvCaseFixer('plural|snake',$resource).'_detach']??[]);
    $modelCollectionInstance->{cvCaseFixer('plural|camel',$resource)}()->detach($toDetach);

    return $modelCollectionInstance;
  }

  protected function detacher($resource){
    static::externalDetacher($this->getModelCollectionInstance(),$resource,$this->getFields());

    return $this;
  }
// relation stores
  public static function externalStoreRelation($modelCollectionInstance,$resource,$fields){
    return static::externalAttacher($modelCollectionInstance,$resource,$fields);
  }

  protected function storeRelation($resource){
    return $this->attacher($resource);
  }
// relation updaters
  protected function externalUpdateRelation($modelCollectionInstance,$resource,$fields){
    static::externalDetacher($modelCollectionInstance,$resource,$fields);
    static::externalAttacher($modelCollectionInstance,$resource,$fields);

    return $modelCollectionInstance;
  }

  protected function updateRelation($resource){
    static::externalUpdateRelation($this->getModelCollectionInstance(),$resource,$this->getFields());

    return $this;
  }

// dissociaters
  protected function externalDissociateResource($relatedResource,$relatedResourceKeys=[],$foreingColumn = null){
    $relatedResourceModel = '\App\Models\\'.cvCaseFixer('singular|studly',$relatedResource);
    $relatedResourceRows  = $relatedResourceModel::byResource($this->getSlugSingularName(),$this->getModelCollectionInstance()->id)
      ->noKeys($relatedResourceKeys)->get();
    $foreingColumn = $foreingColumn ??  $this->getSnakeSingularName().'_id';

    foreach($relatedResourceRows as $relatedResource)
      if(!$relatedResource->fill([$foreingColumn=>null])->save())
        return false;

    return true;
  }

  protected function dissociateResource($resource,$resourceKeys=[],$foreingColumn = null){
    return static::externalDissociateResource($resource,$resourceKeys,$foreingColumn);
  }

// Associaters
  public static function cvAssociacionInteractionsLoader(){
    return new \Crudvel\Libraries\CvAssociations\CvAssociacionInteractions();
  }

  public static function externalAssociateResource($keyValueResource,$relatedResource,$relatedResourceKeys=[]){
    $relatedResourceModel = '\App\Models\\'.cvCaseFixer('singular|studly',$relatedResource);

    foreach($relatedResourceModel::keys($relatedResourceKeys)->get() as $currentResource)
      if(!$currentResource->fill($keyValueResource)->save())
        return false;

    return true;
  }

  protected function associateResource($resource,$resourceKeys=[],$foreingColumn = null){
    $resourceModel = '\App\Models\\'.cvCaseFixer('singular|studly',$resource);
    $foreingColumn = $foreingColumn ??  $this->getSnakeSingularName().'_id';
    foreach($resourceModel::keys($resourceKeys)->get() as $resource)
      if(!$resource->fill([$foreingColumn=>$this->getModelCollectionInstance()->id])->save())
        return false;

    return true;
  }

  public static function externalStoreAssociated(){
    return static::externalAssociateResource();
  }

  public static function externalStoreAssociatedOld($keyValueResource,$relatedResource,$relatedResourceKeys=[]){
    return static::externalAssociateResource($keyValueResource,$relatedResource,$relatedResourceKeys);
  }

  protected function storeAssociated($resource,$resourceKeys=[],$foreingColumn = null){
    return $this->associateResource($resource,$resourceKeys,$foreingColumn);
  }

  public static function externalUpdateAssociated($keyValueResource,$relatedResource,$relatedResourceKeys=[]){
    return static::externalAssociateResource($keyValueResource,$relatedResource,$relatedResourceKeys);
  }

  protected function updateAssociated($resource,$resourceKeys=[],$foreingColumn = null){
    return $this->dissociateResource($resource,$resourceKeys,$foreingColumn) &&  $this->associateResource($resource,$resourceKeys,$foreingColumn);
  }*/
// [End Methods]
}
