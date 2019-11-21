<?php namespace Crudvel\Controllers;

use Crudvel\Interfaces\CvCrudInterface;
use Crudvel\Traits\CrudTrait;
use DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
/*
    This is a customization of the controller, some controllers needs to save multiple data on different tables in one step, doing that without transaction is a bad idea.
    so the options is to doing it manually, but it is a lot of code, and it is always the same, to i get that code and put it togheter as methods, so now with the support of
    the anonymous functions, all this code can be reused, saving a lot of time.
*/
class CustomController extends BaseController implements CvCrudInterface{
  //preparing refactyoring
  protected $cvResource;
  protected $modelClassName;
  protected $requestClassName;
  protected $slugPluralName;
  protected $slugSingularName;

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

  //public $baseResourceUrl;
  protected $transStatus;
  protected $committer;
  //public $rowName;
  //public $rowsName;
  //modelo cargado en memoria
  protected $skipModelValidation=false;
  //validador autorizador anonimo
  protected $slugField       = null;
  protected $slugedResponse  = false;
  protected $defaultFields;
  //Acciones que se basan en un solo elemento
  protected $dirtyPropertys;
  protected $debugg          = false;
  protected $actions         = [
    "index",
    "sluged",
    "show",
    "create",
    "store",
    "edit",
    "update",
    "delete",
    "destroy",
    "activate",
    "deactivate",
    "import",
    "importing",
    "export",
    "exporting",
    "exportings",
  ];
  protected $rowActions = [
    "show",
    "edit",
    "delete",
    "update",
    "destroy",
    "activate",
    "deactivate",
    "exporting",
  ];
  protected $viewActions = [
    "index",
    "show",
    "create",
    "edit",
    "import",
  ];
  protected $rowsActions = [
    "index",
    "sluged",
    "import",
    "importing",
    "export",
    "exportings",
  ];

  use CrudTrait;

  public function __construct(...$propertyRewriter){
    $this->autoSetPropertys(...$propertyRewriter);
    \CvResource::boot($this);
    $this->injectCvResource();
  }

  /**
  * function tu prevent this callAction implementation
  *
  * @author benomas benomas@gmail.com
  * @date   2019-06-12
  * @return response
  */
  public function callActionJump($method,$parameters=[]){
    return parent::callAction($method,$parameters);
  }

  public function callAction($method,$parameters=[]){
    if(!$this->getCurrentAction())
      $this->setCurrentAction($method);

    if(!in_array($this->getCurrentAction(),$this->actions))
      return $this->webNotFound();

    //$this->setCurrentUser();
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
        return $this->webNotFound();
      $this->setCurrentActionKey($parameters[$this->getSnakeSingularName()]);
      if(!$this->getModelBuilderInstance()->key($this->getCurrentActionKey())->count())
        return $this->webNotFound();
      $this->setModelCollectionInstance($this->getModelBuilderInstance()->first());
    }
    return parent::callAction($method,$parameters);
  }

  public function preAction($method,$parameters){} //customize actions before normal ejecution

  public function postAction($next){ //customize actions after normal ejecution
    return $next;
  }

  public function modelator($action){} //$this->model

  protected function resetTransaction(){
    $this->committer   = null;
    $this->transStatus = null;
  }

  /**
   * Initialize control variable and start db transaction
   *
   * @author    Beni (benomas@gmail.com) 2016-12-20
   *
   * @return  void
   */
  protected function startTranstaction($committer=null){

    if( isset($this->transStatus) && $this->transStatus)
      return true;

    if(!isset($this->committer) && !$this->committer){
      if($committer)
        $this->committer = $committer;
    }
    else
      return true;

    $this->transStatus='transaction-in-progress';
    DB::beginTransaction();
  }

  /**
   * When a callback return false, the transaction fail script will be excecuted, changing de status of control variable and launching db rollback
   *
   * @author    Beni (benomas@gmail.com) 2016-12-20
   *
   * @return  data type to return
   */
  protected function transactionFail($cBFail=null){
    $this->transStatus='transaction-fail';
    DB::rollBack();

    if($cBFail && is_callable($cBFail))
      $cBFail();
  }

  /**
   * This method is for close the excecution of transactions, if the current value of the control variable is
   * transaction in progress, it will commit the db and change the control variable tu 'transaction completed'.
   * else some transaction step has been failed and the status of the control variable will be updated tu 'transaction-completed-with-error'
   *
   * @author    Beni (benomas@gmail.com) 2016-12-20
   *
   * @return  void
   */
  protected function transactionComplete($committer=null){

    if( isset($this->committer) &&  $this->committer && $this->committer !== $committer)
      return false;

    if($this->transStatus==='transaction-in-progress'){
      DB::commit();

      $this->transStatus='transaction-completed';
    }
    else
      $this->transStatus='transaction-completed-with-error';
  }

  /**
   * Excecute a transaction, and depends on the result of his anonymouse function , it will prepair de scenario for stop excecuting more transactions or
   * just don touch the current status
   *
   * @author    Beni (benomas@gmail.com) 2016-12-20
   *
   * @param    Anonymouse function    callBack    script to be excecuted has the main purpose of the transaction
   *
   * @param    Anonymouse function    cBFail      script to be excecuted when the transaction fails
   *
   * @param    Anonymouse function    cBSuccess   script to be excecuted when the transaction success
   *
   * @return  void
   */
  protected function testTransaction($callback,$errorCallBack=null,$tryCatch=true){
    $errorException=null;
    if($this->transStatus === 'transaction-in-progress' && is_callable($callback)){
      if($tryCatch && !$this->debugg){
        try{
          if(!$callback())
            $this->transactionFail();
        }
        catch(\Exception $e){
          $errorException=$e;
          $this->transactionFail();
        }
      }
      else{
        if(!$callback())
          $this->transactionFail();
      }
      if($this->transStatus==='transaction-fail' && is_callable($errorCallBack) && !$this->debugg)
        $errorCallBack($errorException);
    }
  }

  /**
   * Check if the curret set of transactions were all successfull completed or someone fail
   *
   * @author    Beni (benomas@gmail.com) 2016-12-20
   *
   * @return  boolean
   */
  protected function isTransactionCompleted(){
    return $this->transStatus==='transaction-completed';
  }

  protected function isThisCommitter($committer=null){
    if( !isset($this->committer))
      return true;
    return $this->committer === $committer;
  }

  public function persist($callBack=null){
    $this->resetTransaction();
    $this->startTranstaction();
    $this->testTransaction(function() use($callBack){
      $this->modelInstance = $this->modelInstance ?? $this->modelInstanciator(true);
      $this->modelInstance->fill($this->fields);
      if(!empty($this->fields['created_by']))
        $this->modelInstance->created_by=$this->fields['created_by'];
      if(!empty($this->fields['updated_by']))
        $this->modelInstance->updated_by=$this->fields['updated_by'];
      $this->dirtyPropertys = $this->modelInstance->getDirty();
      if(!$this->modelInstance->save())
        return false;
      if($callBack && is_callable($callBack))
        return $callBack();
      return true;
    });
    $this->transactionComplete();
    return $this->isTransactionCompleted();
  }

  public function activate($id){
    $this->fields["status"]=1;
    return $this->update($id);
  }

  public function deactivate($id){
    $this->fields["status"]=0;
    return $this->update($id);
  }

  public function export(){
    $data = [];
    $this->requestInstance->langsToImport($this->modelInstanciator(true)->getFillable());
    if(($rows = $this->getModelBuilderInstance()->get()))
      foreach($rows as $key=>$row)
        foreach ($this->requestInstance->exportImportProperties as $label=>$field)
          $data[$key][] = $this->requestInstance->exportPropertyFixer($field,$row);

    array_unshift($data, array_keys($this->requestInstance->exportImportProperties));
    Excel::create(trans("crudvel/".$this->langName.".rows_label")." ".intval(microtime(true)), function ($excel) use ($data) {
      $excel->sheet('Hoja1', function ($sheet) use ($data) {
        $sheet->fromArray($data, "", "A1", true, false);
      });
    })->export('xlsx');
  }


  public function exportings(){
    //Todo, implement a general logic for these methods
    return false;
  }
  public function exporting($id){
    //Todo, implement a general logic for these methods
    return false;
  }
  public function importing(){
    $fail=true;
    ini_set('max_execution_time',300);

    if ($this->requestInstance->hasFile('importation_file')) {
      $fail=false;
      $extension = $this->requestInstance->file('importation_file')->getClientOriginalExtension();
      $name      = uniqid();
      $filename  = $name . "." . $extension;
      $path      = public_path() . "/upload/importing/" . $filename;

      $this->requestInstance->file('importation_file')->move(public_path() . "/upload/importing/", $filename);
      $reader = Excel::load($path)->get();
      $this->requestInstance->inicializeImporter($this->modelInstanciator(true)->getFillable());
      $reader->each(function ($row){
        $this->resetTransaction();
        $this->startTranstaction();
        $this->testTransaction(function() use($row){
          $this->requestInstance->firstImporterCall($row);
          $this->requestInstance->fields = [];
          foreach ($this->requestInstance->exportImportProperties as $label=>$field)
            if(($dataFiled = $this->requestInstance->importPropertyFixer($label,$row))!==null)
              $this->requestInstance->fields[$field] = $dataFiled;

          if(!$this->requestInstance->validateImportingRow($row)){
            $this->requestInstance->changeImporter("validationErrors",$this->requestInstance->currentValidator->errors());
            return false;
          }

          $this->requestInstance->changeImporter();
          if(($model = (  $this->requestInstance->currentAction==="store"?
                            $this->modelInstanciator(true):
                            $this->modelInstanciator()->key($row->{$this->requestInstance->slugedImporterRowIdentifier()})->first()
                      )
              )
          ){
              $model->fill($this->requestInstance->fields);
              if(!$model->isDirty()){
                $this->requestInstance->changeTransactionType("Sin cambios");
                return $this->importCallBack();
              }
              if($model->save())
                return $this->importCallBack();

              $this->requestInstance->changeImporter("validationErrors",'Error de transacción');
          }
          return false;
        });
        $this->transactionComplete();
      });
      @unlink($path);
    }

    if($this->requestInstance->wantsJson())
      return $fail?$this->apiFailResponse():$this->apiSuccessResponse(["data"=>$this->requestInstance->importResults,"status"=>trans("crudvel.api.success")]);

    Session::flash('importResults', $this->requestInstance->importResults);
    return $fail?$this->webFailResponse():$this->webSuccessResponse();
  }

  public function importCallBack(){
    return true;
  }

  public function addActions(...$moreActions){
    $this->actions=array_merge($this->actions,$moreActions);
  }

  public function addRowActions(...$moreActions){
    $this->rowActions=array_merge($this->rowActions,$moreActions);
  }

  public function addViewActions(...$moreActions){
    $this->viewActions=array_merge($this->viewActions,$moreActions);
  }

  public function setSlugField(){
    if($this->slugField)
      return true;
    if(in_array("slug",$this->selectables))
      return $this->slugField = "slug";
    if(in_array("name",$this->selectables))
      return $this->slugField = "name";
    if(in_array("title",$this->selectables))
      return $this->slugField = "title";
    return false;
  }

  public function getCrudvel(){
    return $this->crudvel??null;
  }
  public function getPrefix(){
    return $this->prefix??null;
  }
  public function getClassType(){
    return $this->classType??null;
  }
  public function getBaseClass(){
    return $this->baseClass??null;
  }
  public function getResource(){
    return $this->resource??null;
  }
  public function getTransStatus(){
    return $this->transStatus??null;
  }
  public function getCommitter(){
    return $this->committer??null;
  }
  public function getCrudObjectName(){
    return $this->crudObjectName??null;
  }
  public function getModelSource(){
    return $this->modelSource??null;
  }
  public function getRequestSource(){
    return $this->requestInstanceClass??null;
  }
  public function getRows(){
    return $this->rows??null;
  }
  public function getRow(){
    return $this->row??null;
  }
  public function getRowName(){
    return $this->rowName??null;
  }
  public function getRowsName(){
    return $this->rowsName??null;
  }
  public function getMainTableName(){
    return $this->mainTableName??null;
  }
  public function getSkipModelValidation(){
    return $this->skipModelValidation??null;
  }
  public function getModel(){
    return $this->model??null;
  }
  public function getModelInstance(){
    return $this->modelInstance??null;
  }
  public function getUserModel(){
    return $this->setUserModelBuilderInstance()??null;
  }
  public function getRequest(){
    return $this->requestInstance??null;
  }
  public function getFields(){
    return $this->fields??null;
  }
  public function getSlugField(){
    return $this->slugField??null;
  }
  public function getSlugedResponse(){
    return $this->slugedResponse??null;
  }
  public function getDefaultFields(){
    return $this->defaultFields??null;
  }
  public function getCurrentUser(){
    return $this->currentUser??null;
  }
  public function getDirtyPropertys(){
    return $this->dirtyPropertys??null;
  }
  public function getLangName(){
    return $this->langName??null;
  }
  public function getDebugg(){
    return $this->debugg??null;
  }
  public function getActions(){
    return $this->actions??null;
  }
  public function getRowActions(){
    return $this->rowActions??null;
  }
  public function getViewActions(){
    return $this->viewActions??null;
  }
  public function getRowsActions(){
    return $this->rowsActions??null;
  }
  //----
  public function getModelClassName(){
    return $this->modelClassName??null;
  }
  public function getRequestClassName(){
    return $this->requestClassName??null;
  }


  public function getPaginators(){
    return $this->paginators??null;
  }
  public function getPaginator($paginator=null){
    return $this->paginators[$paginator]??$this->paginators['cv-simple-paginator']??null;
  }
}
