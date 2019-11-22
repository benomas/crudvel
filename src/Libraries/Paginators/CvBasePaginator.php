<?php

namespace Crudvel\Libraries\Paginators;
use Crudvel\Libraries\DbEngine\EngineContainer;
use Crudvel\Interfaces\CvCrudInterface;
use Crudvel\Traits\CrudTrait;

class CvBasePaginator implements CvCrudInterface
{
  use CrudTrait;

  //arreglo con columnas que se permiten filtrar en paginado, si se setea con false, no se permitira filtrado, si se setea con null o no se setea, no se pondran restricciones en filtrado
  protected $filterables = null;
  //arreglo con columnas que se permiten ordenar en paginado, si se setea con false, no se permitira ordenar, si se setea con null o no se setea, no se pondran restricciones en ordenar
  protected $orderables = null;
  //arreglo con columnas que se permiten seleccionar en paginado, si se setea con false, no se permitira seleccionar de forma especifica, si se setea con null o no se setea, no se pondran restricciones en seleccionar de forma especifica
  protected $selectables = null;
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
    $this->setFlexPaginable($this->getPaginatorDefiner()->getFlexPaginable());
    $this->setSelectables($this->getPaginatorDefiner()->getSelectables());
    $this->dbEngineContainer = new EngineContainer(config('database.default'));
    //$this->setJoinables($this->getPaginatorDefiner()->getJoinables());
    $this->setBasicPropertys();
  }

  public function fixSelectQuery(){
    pdd('asdasd');
    //si selectables esta definida en false, no se aceptara seleccion personalizada de columnas
    if($this->getSelectables()===false)
      return $this->setSelectQuery(false);

    //definimos las columnas que deberan mandarse como respuesta a la petición
    return $this->setSelectQuery(arrayIntersect($this->getSelectQuery(),$this->getSelectables()));
  }

  public function fixFilterQuery(){
    //si filterables esta definida en false, no se aceptara filtrado por ninguna columna
    if(isset($this->filterables) && $this->filterables===false)
      return $this->filterQuery=false;

    //definimos las columnas que podran ser filtradas mediante fluent eloquent
    $this->filterQuery = arrayIntersect($this->paginate["filterQuery"]??null,$this->filterables??null,true);
    //$this->dbEngineContainer->setFilterQueryString($this->filterQuery);
  }

  public function fixOrderables(){
    if(isset($this->orderables) && $this->orderables===false)
      return $this->orderBy=false;

    //definimos la columna de ordenamiento
    $this->orderBy = arrayIntersect([$this->paginate["orderBy"]??null],$this->orderables??null);

    if(nonEmptyArray($this->orderBy))
      return $this->orderBy=$this->orderBy[0];

    if(is_array($this->orderBy))
      $this->orderBy=null;
  }

  //Getters start
  public function getPaginate(){
    return $this->paginate??null;
  }
  public function getFlexPaginable(){
    return $this->flexPaginable??null;
  }
  public function getSelectables(){
    return $this->selectables??null;
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
    return $this->filterables??null;
  }
  public function getOrderables(){
    return $this->orderables??null;
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
  //Getters end

  //Setters start
  protected function setBasicPropertys(){
    $paginate = $this->getPaginateFields();
    $this->setLimit(fixedIsInt($paginate["limit"]??null)?$paginate["limit"]:null);
    $this->setPage(fixedIsInt($paginate["page"]??null)?$paginate["page"]:null);
    $this->setAscending($paginate["ascending"]??null);
    $this->setByColumn($paginate["byColumn"]??null);
    $this->setSearchObject($paginate["searchObject"]??'');
  }
  public function setPaginate($paginate=null){
    $this->paginate = $paginate??null;
    return $this;
  }
  public function setFlexPaginable($flexPaginable=null){
    $this->flexPaginable = $flexPaginable??null;
    return $this;
  }
  public function setSelectables($selectables=null){
    return $this->selectables??null;
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
  //Setters end

  //rewrite this method for custom logic
  public function unions(){
  }
  public function setFilterables($filterables){
    $this->filterables=$filterables;
    return $this;
  }
  public function setOrderables($orderables){
    $this->orderables=$orderables;
    return $this;
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
}


