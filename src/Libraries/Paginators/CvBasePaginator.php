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
  protected $model;
  protected $modelInstance;
  //array de comparaciones validas
  protected $logicConnectors  =[
    'and'=>'where'
    ,'or'=>'Orwhere'
  ];
  public $unsolvedColumns;

  public function __construct($paginatorDefiner=null){
    if(!$paginatorDefiner || !is_object($paginatorDefiner))
      return $this;
    $this->paginatorDefiner = $paginatorDefiner;
    $this->flexPaginable     = $this->paginatorDefiner->getFlexPaginable();
    $this->selectables       = $this->paginatorDefiner->getSelectables();
    $this->paginate          = $this->paginatorDefiner->getRequestInstance()->get("paginate");
    $this->model             = $this->paginatorDefiner->getModel();
    $this->modelInstance     = $this->paginatorDefiner->getModelInstance();
    $this->dbEngineContainer = new EngineContainer($this->model->newModelInstance()->getConnectionName());
    $this->joinables         = $this->paginatorDefiner->getJoinables();
    $this->setBasicPropertys();
  }

  protected function setBasicPropertys(){
    $this->limit        = fixedIsInt($this->paginate["limit"]??null)?$this->paginate["limit"]:null;
    $this->page         = fixedIsInt($this->paginate["page"]??null)?$this->paginate["page"]:null;
    $this->ascending    = $this->paginate["ascending"]??null;
    $this->byColumn     = $this->paginate["byColumn"]??null;
    $this->searchObject = $this->paginate["searchObject"]??'';
  }

  public function fixSelectQuery(){
    //si selectables esta definida en false, no se aceptara seleccion personalizada de columnas
    if(isset($this->selectables) && $this->selectables===false)
      return $this->selectQuery=false;

    //definimos las columnas que deberan mandarse como respuesta a la petición
    $this->selectQuery = arrayIntersect($this->paginate["selectQuery"]??null,$this->selectables??null);
  }

  public function fixFilterQuery(){
    //si filterables esta definida en false, no se aceptara filtrado por ninguna columna
    if(isset($this->filterables) && $this->filterables===false)
      return $this->filterQuery=false;

    //definimos las columnas que podran ser filtradas mediante fluent eloquent
    $this->filterQuery = arrayIntersect($this->paginate["filterQuery"]??null,$this->filterables??null,true);
    $this->dbEngineContainer->setFilterQueryString($this->filterQuery);
  }

  public function fixOrderables(){
    if(isset($this->orderables) && $this->orderables===false)
      return $this->orderBy=false;

    //definimos la columna de ordenamiento
    $this->orderBy = arrayIntersect([$this->paginate["orderBy"]??null],$this->orderables??null);

    if(customNonEmptyArray($this->orderBy))
      return $this->orderBy=$this->orderBy[0];

    if(is_array($this->orderBy))
      $this->orderBy=null;
  }

  public function getFilterables(){
    return $this->filterables??null;
  }
  public function getOrderables(){
    return $this->orderables??null;
  }
  public function getSelectables(){
    return $this->selectables??null;
  }
  public function getJoinables(){
    return $this->joinables??null;
  }
  public function getAscending(){
    return $this->ascending??null;
  }
  public function getByColumn(){
    return $this->byColumn??null;
  }
  public function getFilterQuery(){
    return $this->filterQuery??null;
  }
  public function getLimit(){
    return $this->limit??null;
  }
  public function getOrderBy(){
    return $this->orderBy??null;
  }
  public function getPage(){
    return $this->page??null;
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
  public function getContainer(){
    return $this->paginatorDefiner??null;
  }
  public function getPaginate(){
    return $this->paginate??null;
  }
  public function getFlexPaginable(){
    return $this->flexPaginable??null;
  }
  public function getModel(){
    return $this->model??null;
  }
  public function getModelInstance(){
    return $this->modelInstance??null;
  }

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
  public function setSelectables($selectables){
    $this->selectables=$selectables;
    return $this;
  }
  public function setModel($model){
    $this->model=$model;
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


