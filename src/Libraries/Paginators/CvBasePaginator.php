<?php

namespace Crudvel\Libraries\Paginators;
use Crudvel\Libraries\DbEngine\EngineContainer;
use Crudvel\Interfaces\CvCrudInterface;
use Crudvel\Traits\CrudTrait;

class CvBasePaginator implements CvCrudInterface
{
  use CrudTrait;

  protected $cvResourceInstance;

  //arreglo con columnas que se permiten filtrar en paginado, si se setea con false, no se permitira filtrado, si se setea con null o no se setea, no se pondran restricciones en filtrado
  protected $filterables = null;
  //arreglo con columnas que se permiten ordenar en paginado, si se setea con false, no se permitira ordenar, si se setea con null o no se setea, no se pondran restricciones en ordenar
  protected $orderables = null;
  //mapa de columnas join,
  protected $joinables = null;
  //boleado que indica si el ordenamiento sera ascendente o no
  protected $ascending;
  //boleado que indica si se permite filtrado columna por columna
  protected $byColumn;
  //arreglo de columnas a filtrar, cuando byColumn es verdadero, se debera agregar un valor de busqueda por cada columna
  protected $filterQuery;
  //entero con limite de valores a regresar por paginado
  protected $limit;
  //cadena con nombre de columna por la que se ordenaran los resultados de paginado
  protected $orderBy;
  //entero con pagina que se requiere obtener por paginado
  protected $page;
  //arreglo de columnas a seleccionar
  protected $selectQuery;
  //array de comparaciones validas
  protected $comparators       = ["=","<",">","<>","<=",">=","like"];
  //array de comparaciones validas
  protected $comparator        = null;
  protected $paginateCount     = 0;
  protected $paginateData      = null;
  protected $paginate          = null;
  protected $flexPaginable     = true;
  protected $searchObject      = '';
  protected $searchMode        = 'cv-simple-paginator';
  protected $dbEngineContainer = null;
  //array de comparaciones validas
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
    return $this->getSelectables();
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
    if($this->fixedSelectables()===false)
      return $this->setSelectQuery(false);
    //define columns to be present in the response
    if(!noEmptyArray($this->getSelectQuery()))
      return $this->setSelectQuery($this->fixedSelectables());
    return $this->setSelectQuery(arrayIntersect($this->getSelectQuery(),$this->fixedSelectables()));
  }

  public function fixFilterQuery(){
    //si filterables esta definida en false, no se aceptara filtrado por ninguna columna
    if($this->fixedFilterables()===false)
      return $this->setFilterQuery(false);

    $fixFilterQuery = [];
    foreach((array) $this->getFilterQuery() as $key=>$value)
      if(in_array($key,$this->fixedFilterables()))
        $fixFilterQuery[$key]=$value;
    return $this->setFilterQuery($fixFilterQuery);
    //$this->dbEngineContainer->setFilterQueryString($this->filterQuery);
  }

  public function fixOrderables(){
    if($this->fixedOrderables()===false)
      return $this->setOrderBy(false);

    //definimos la columna de ordenamiento
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
    $this->model->$lop($field,$eOp,$filter['value']);
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
   * Responde una peticion http de forma paginada de acuerdo a la combinacion de parametros mandados
   */
  public function processPaginatedResponse() {
    //si el modelo no esta definido o es nulo
    if($this->getModelBuilderInstance()===null)
      return ;
    //si existe un array de columnas a seleccionar
    if(noEmptyArray($this->getSelectQuery()))
      $this->fixSelectables();

    if($this->getModelBuilderInstance()===null || $this->getModelBuilderInstance()->count() === 0)
      return ;

    $this->tempQuery();
    //si existe un array de columnas a filtrar

    if(noEmptyArray($this->getFilterQuery()))
      $this->filter();
    $this->paginateCount = $this->getModelBuilderInstance()->count();
    //si se solicita limitar el numero de resultados
    if($this->limit){
      $this->getModelBuilderInstance()->limit($this->limit);
      //si se especifica una pagina a regresar
      if($this->page && $this->paginateCount >= $this->limit * ($this->page-1))
        $this->getModelBuilderInstance()->skip($this->limit * ($this->page-1));
    }

    if ($this->orderBy)
      $this->getModelBuilderInstance()->orderBy($this->orderBy,$this->ascending==1?"ASC":"DESC");

    if($this->getModelCollectionInstance() && !empty($this->getModelCollectionInstance()->id))
      $this->getModelBuilderInstance()->id($this->getModelCollectionInstance()->id,false);
  }

  public function paginateResponder(){
    if(!$this->getModelBuilderInstance())
      return $this->getRootInstance()->apiSuccessResponse([
        "data"   =>[],
        "count"  =>0,
        "message" =>trans("crudvel.api.success")
      ]);
    if(!empty($this->getModelCollectionInstance()->id) || $this->getRootInstance()->getForceSingleItemPagination())
      $this->paginateData=$this->getModelBuilderInstance()->first();

    if(!$this->paginateData && !$this->getRootInstance()->getSlugedResponse())
      $this->paginateData = $this->getModelBuilderInstance()->get();

    if(!$this->paginateData){
      $keyed = $this->getModelBuilderInstance()->get()->keyBy(function ($item) {
        return Str::slug($item[$this->getRootInstance()->getSlugField()]);
      });
      $this->paginateData = $keyed->all();
    }
    return $this->getRootInstance()->apiSuccessResponse([
      "data"    => $this->paginateData,
      "count"   => $this->paginateCount,
      "message" => trans("crudvel.api.success")
    ]);
  }

  /**
   * Responde una peticion http de forma paginada de acuerdo a la combinacion de parametros mandados
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
  //Getters end

  //Setters start
  public function setPaginate($paginate=null){
    $this->paginate = $paginate??null;
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
    $this->comparator=$comparator;
    return $this;
  }
  public function setUnsolvedColumns($unsolvedColumns=null){
    $this->unsolvedColumns=$unsolvedColumns;
    return $this;
  }
  //Setters end
}


