<?php

namespace Crudvel\Libraries\Paginators;
use Crudvel\Libraries\DbEngine\EngineContainer;
use Crudvel\Interfaces\CvCrudInterface;

class CvBasePaginator implements CvCrudInterface
{
  use \Crudvel\Traits\CrudTrait;
  protected $cvResourceInstance;

  //valid columns to be filtered
  protected $filterables = null;
  //valid columns to be ordered
  protected $orderables = null;
  //map of joined columns
  protected $joinables = null;
  //order direction
  protected $ascending;
  //is filter by column enabled?
  protected $byColumn;
  //query to be filtered, when byColumn is enabled, every column can have is own individual search
  protected $filterQuery;
  //limit of records to be collected
  protected $limit;
  //name of the column to order
  protected $orderBy;
  //wich page will be required
  protected $page;
  //list of columns to be selected
  protected $selectQuery;
  //valir comparator operators
  protected $comparators       = ["=","<",">","<>","<=",">=","like"];
  protected $comparator        = null;
  protected $paginateCount     = 0;
  protected $paginateData      = null;
  protected $paginate          = null;
  protected $flexPaginable     = true;
  protected $searchObject      = '';
  protected $searchMode        = 'cv-simple-paginator';
  protected $dbEngineContainer = null;
  //valid conectors
  protected $logicConnectors  =[
    'and'=>'where'
    ,'or'=>'Orwhere'
  ];
  public $unsolvedColumns;

  public function __construct(){
    $this->injectCvResource();
    $this->setPaginate($this->getPaginateFields());
    $this->setFlexPaginable($this->getRootInstance()->getFlexPaginable());
    $this->loadBasicPropertys();
    $this->setDbEngineContainer(new EngineContainer($this->getModelclass()::cvIam()->getConnectionName()));
  }

  /**
   * load paginate params from http request
   */
  public function extractPaginate(){
    //if not correct paginate params
    if(!noEmptyArray($this->getPaginate())){
      if(!$this->getFlexPaginable())
        return false;
      $this->getRootInstance()->setBadPaginablePetition(true);
    }
    $this->fixSelectQuery()->fixFilterQuery()->fixOrderables();
    return true;
  }

  public function fixedSelectables(){
    return array_intersect((array) $this->getSelectQuery(),(array) $this->getSelectables());
  }
  public function fixedFilterables(){
    if($this->getFilterables()!==null)
      return $this->getFilterables();
    return $this->fixedSelectables();
  }
  public function fixedOrderables(){
    if($this->getOrderables()!==null)
      return $this->getOrderables();
    return $this->fixedFilterables();
  }

  public function fixSelectQuery(){
    //if selectables is disabled
    if($this->getSelectables()===false)
      return $this->setSelectQuery(false);
    //define columns to be present in the response
    if(noEmptyArray($this->getSelectQuery()))
      return $this->setSelectQuery($this->fixedSelectables());
    return $this->setSelectQuery($this->getSelectables());
  }

  public function fixFilterQuery(){
    //if filterables is false, filter by column will no be supported
    if($this->fixedFilterables()===false)
      return $this->setFilterQuery(false);

    $fixFilterQuery = [];
    foreach((array) $this->getFilterQuery() as $key=>$value)
      if(in_array($key,$this->fixedFilterables()))
        $fixFilterQuery[$key]=$value;
    $this->setFilterQuery($fixFilterQuery)->getDbEngineContainer()->setFilterQueryString($this->getFilterQuery());
    return $this;
  }

  public function fixOrderables(){
    if($this->fixedOrderables()===false)
      return $this->setOrderBy(false);

    //set order by column
    $this->setOrderBy(arrayIntersect([$this->getOrderBy()],$this->fixedOrderables())[0]??null);
    return $this;
  }

  /**
  * this function is very importan, because create a temp table with que current query builder, this work as a view
  * and simplify the requeriment to fix column names when multiple tables are required
  */
  public function tempQuery(){
    $querySql = $this->getModelBuilderInstance()->toSql();
    $this->getModelBuilderInstance()
      ->setQuery(\DB::table(\DB::raw("($querySql) as cv_pag"))
      ->setBindings($this->getModelBuilderInstance()
      ->getBindings())
    );
  }


  protected function loadBasicPropertys(){
    $paginate = $this->getPaginateFields();
    $this->setLimit(fixedIsInt($paginate["limit"]??null)?$paginate["limit"]:null)
    ->setPage(fixedIsInt($paginate["page"]??null)?$paginate["page"]:null)
    ->setAscending($paginate["ascending"]??null)
    ->setByColumn($paginate["byColumn"]??null)
    ->setSearchObject($paginate["searchObject"]??'')
    ->setSelectQuery($paginate["selectQuery"]??null)
    ->setFilterQuery($paginate["filterQuery"]??null)
    ->setOrderBy($paginate["orderBy"]??null);
  }
  public function filter() {
  }

  public function applyCustomFilter($field,$filter){
    $lop = $filter['lOp'] ?? 'or';
    $eOp = $filter['eOp'] ?? 'like';
    if(empty($this->logicConnectors[$lop]))
      jdd('invalid custom filter, require to define lOp property');
    if(!in_array($eOp,$this->comparators))
      jdd('invalid custom filter, require to define eOp property');
    $lop = $this->logicConnectors[$lop];
    if($eOp === 'like')
      $this->getModelBuilderInstance()->$lop($field,$eOp,'%'.$filter['value'].'%');
    else
      $this->getModelBuilderInstance()->$lop($field,$eOp,$filter['value']);
  }

  public function fixables($property){
    $simpleColumns=$this->$property;
    if($simpleColumns && count($simpleColumns)){
      $columns = $this->getRootInstance()->modelInstanciator(true)->getTableColumns();
      foreach ($simpleColumns as $simpleColumnKey => $simpleColumnValue){
        if (in_array($simpleColumnValue,$columns))
          $this->$property[$simpleColumnKey] = $this->getRootInstance()->getMainTableName().$simpleColumnValue." AS ".$simpleColumnValue;
        else
          $this->addUnsolvedColumn($simpleColumnValue);
      }
    }
  }

  public function fixSelectables(){
    $this->fixables('selectQuery');
    $this->inyectAddeds();
  }

  public function fixFilterables(){
    $this->fixables('filterQuery');
  }

  public function fixOrderBy(){
    $this->orderBy = $this->mainTableName.$this->orderBy;
  }

  public function inyectAddeds(){
    foreach((array) $this->getUnsolvedColumns() as $key=>$unsolved){
      $subqueryTest = 'added'.\Str::studly($unsolved);
      if(method_exists($this->getRootInstance(),$subqueryTest)){
        $this->getModelBuilderInstance()->addSelect([$unsolved =>$this->getRootInstance()->{$subqueryTest}()]);
        $this->removeUnsolvedColumn($key);
      }
    }
  }
  /**
   * make http request with a paginate set of data, accorded to the given parameters
   */
  public function processPaginatedResponse() {
    //if no model builder instance defined
    if($this->getModelBuilderInstance()===null)
      return ;
      //if it is not a select query defined
    if(noEmptyArray($this->getSelectQuery()))
      $this->fixSelectables();

    if($this->getModelBuilderInstance()===null || $this->getModelBuilderInstance()->count() === 0)
      return ;

    $this->tempQuery();
    //if it is not a filter quary defined
    if(noEmptyArray($this->getFilterQuery()))
      $this->filter();
    $this->setPaginateCount($this->getModelBuilderInstance()->count());
    //if limit for que query is defined
    if($this->getLimit()){
      $this->getModelBuilderInstance()->limit($this->getLimit());
      //if a page number to get is defined
      if($this->getPage() && $this->getPaginateCount() >= $this->getLimit() * ($this->getPage()-1))
        $this->getModelBuilderInstance()->skip($this->getLimit() * ($this->getPage()-1));
    }

    if ($this->getOrderBy())
      $this->getModelBuilderInstance()->orderBy($this->getOrderBy(),$this->getAscending()==1?"ASC":"DESC");

    if($this->getModelCollectionInstance() && !empty($this->getModelCollectionInstance()->id))
      $this->getModelBuilderInstance()->id($this->getModelCollectionInstance()->id,false);
  }

  public function paginateResponder(){
    $this->getModelBuilderInstance()->select($this->getSelectQuery());
    // TODO: include this validation || !$this->getModelBuilderInstance()->count()
    if(!$this->getModelBuilderInstance())
      return $this->getRootInstance()->apiSuccessResponse([
        "data"   =>[],
        "count"  =>0,
        "message" =>trans("crudvel.api.success")
      ]);
    if(!empty($this->getModelCollectionInstance()->id) || $this->getRootInstance()->getForceSingleItemPagination())
      $this->setPaginateData($this->getModelBuilderInstance()->first());

    if(!$this->getPaginateData() && !$this->getRootInstance()->getSlugedResponse())
      $this->setPaginateData($this->getModelBuilderInstance()->get());

    if(!$this->getPaginateData()){
      $keyed = $this->getModelBuilderInstance()->get()->keyBy(function ($item) {
        return cvSlugCase($item[$this->getRootInstance()->getSlugField()]);
      });
      $this->setPaginateData($keyed->all());
    }
    return $this->getRootInstance()->apiSuccessResponse([
      "data"    => $this->getPaginateData(),
      "count"   => $this->getPaginateCount(),
      "message" => trans("crudvel.api.success")
    ]);
  }

  /**
   * respond http request with a paginate set of data, accorded to the given parameters
   */
  public function paginatedResponse() {
    $this->processPaginatedResponse();
    return $this->paginateResponder();
  }

  public function noPaginatedResponse(){
    $response = $this->getModelCollectionInstance();
    $selectables = $this->getSelectables();
    if($selectables && count($selectables)){
      $this->selectQuery = $selectables;
      $this->fixSelectables();
      if($this->getModelCollectionInstance() && $this->getModelCollectionInstance()->id)
        $this->getModelBuilderInstance()->id($this->getModelCollectionInstance()->id);
      $response = $this->getModelBuilderInstance()->select($selectables)->first();
    }

    return $this->getRootInstance()->apiSuccessResponse($response);
  }

  public function addUnsolvedColumn($unsolvedColumn=null){
    if($unsolvedColumn)
      $this->unsolvedColumns[]=$unsolvedColumn;
    return $this;
  }

  public function removeUnsolvedColumn($unsolvedKey=null){
    if($unsolvedKey !== null)
      unset($this->unsolvedColumns[$unsolvedKey]);
    return $this;
  }
  //Getters start
  public function getPaginate(){
    return $this->paginate??null;
  }
  public function getFlexPaginable(){
    return $this->flexPaginable??null;
  }
  public function getSelectables(){
    return $this->getRootInstance()->getSelectables();
  }
  public function getJoinables(){
    return $this->joinables??null;
  }
  public function getLimit(){
    return $this->limit??null;
  }
  public function getPage(){
    return $this->page??null;
  }
  public function getAscending(){
    return $this->ascending??null;
  }
  public function getByColumn(){
    return $this->byColumn??null;
  }
  public function getSearchObject(){
    return $this->searchObject??null;
  }
  public function getFilterables(){
    return $this->getRootInstance()->getFilterables();
  }
  public function getOrderables(){
    return $this->getRootInstance()->getOrderables();
  }
  public function getFilterQuery(){
    return $this->filterQuery??null;
  }
  public function getOrderBy(){
    return $this->orderBy??null;
  }
  public function getSelectQuery(){
    return $this->selectQuery??null;
  }
  public function getComparators(){
    return $this->comparators??null;
  }
  public function getComparator(){
    return $this->comparator??null;
  }
  public function getPaginateCount(){
    return $this->paginateCount??null;
  }
  public function getPaginateData(){
    return $this->paginateData??null;
  }
  public function getUnsolvedColumns(){
    return $this->unsolvedColumns??null;
  }
  public function getDbEngineContainer(){
    return $this->dbEngineContainer??null;
  }
  //Getters end

  //Setters start
  public function setPaginateCount($paginateCount=null){
    $this->paginateCount = $paginateCount??null;
    return $this;
  }
  public function setPaginate($paginate=null){
    $this->paginate = $paginate??null;
    return $this;
  }
  public function setPaginateData($paginateData=null){
    $this->paginateData = $paginateData??null;
    return $this;
  }
  public function setFlexPaginable($flexPaginable=null){
    $this->flexPaginable = $flexPaginable??null;
    return $this;
  }
  public function setJoinables($joinables){
    $this->joinables = $joinables??null;
    return $this;
  }
  public function setLimit($limit=null){
    $this->limit = $limit??null;
    return $this;
  }
  public function setPage($page=null){
    $this->page = $page??null;
    return $this;
  }
  public function setAscending($ascending=null){
    $this->ascending = $ascending??null;
    return $this;
  }
  public function setByColumn($byColumn=null){
    $this->byColumn=$byColumn??null;
    return $this;
  }
  public function setSearchObject($searchObject=null){
    $this->searchObject=$searchObject??null;
    return $this;
  }
  public function setSelectQuery($selectQuery=null){
    //customLog($selectQuery);
    $this->selectQuery=$selectQuery??null;
    return $this;
  }
  public function setFilterQuery($filterQuery=null){
    $this->filterQuery=$filterQuery??null;
    return $this;
  }
  public function setOrderBy($orderBy=null){
    $this->orderBy=$orderBy??null;
    return $this;
  }
  public function setComparator($comparator=null){
    $this->comparator=$comparator??null;
    return $this;
  }
  public function setUnsolvedColumns($unsolvedColumns=null){
    $this->unsolvedColumns=$unsolvedColumns??null;
    return $this;
  }
  public function setDbEngineContainer($dbEngineContainer=null){
    $this->dbEngineContainer=$dbEngineContainer??null;
    return $this;
  }
  //Setters end
}
