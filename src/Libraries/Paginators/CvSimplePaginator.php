<?php

namespace Crudvel\Libraries\Paginators;
use Crudvel\Libraries\Paginators\CvPaginate;

class CvSimplePaginator extends CvBasePaginator implements CvPaginate
{
  protected $comparator  = "like";

  /**
   * Carga parametros para paginacion basado en las propiedades pasados desde la peticion http
   *
   * @author Benomas benomas@gmail.com
   * @date   2017-05-08
   * @return boolean    if require pagine or not
   */
  public function extractPaginate(){
    //si la peticion http solicita paginación de forma incorrecta
    if(!nonEmptyArray($this->getPaginate())){
      if(!$this->getFlexPaginable())
        return false;
      $this->setSelectQuery($this->getSelectables());
      $this->setFilterQuery($this->getFilterables());
      $this->setBadPaginablePetition(true);
    }
    $this->fixSelectQuery();
    $this->fixFilterQuery();
    $this->fixOrderables();

    //se carga el resto de los parametros para paginar
    return true;
  }

  /**
   * Responde una peticion http de forma paginada de acuerdo a la combinacion de parametros mandados
   *
   * @param model   prefilteredModel  Modelo con filtros default para el servicio a paginar
   *
   * @author Benomas benomas@gmail.com
   * @date   2017-05-08
   * @return respuesta http paginada
   */
  public function processPaginatedResponse() {
    //si el modelo no esta definido o es nulo
    if($this->getModelBuilderInstance()===null)
      return ;
    //add joins
    $this->getPaginatorDefiner()->joins();

    //si existe un array de columnas a seleccionar
    if(nonEmptyArray($this->selectQuery))
      $this->fixSelectables();

    $this->getPaginatorDefiner()->unions();
    if($this->getModelBuilderInstance()===null || $this->getModelBuilderInstance()->count() === 0)
      return ;
    $querySql = $this->getModelBuilderInstance()->toSql();
    $this->getModelBuilderInstance()->setQuery(\DB::table(\DB::raw("($querySql) as cv_pag"))->setBindings($this->getModelBuilderInstance()->getBindings()));
    //si existe un array de columnas a filtrar
    if(nonEmptyArray($this->filterQuery))
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
      return $this->getPaginatorDefiner()->apiSuccessResponse([
        "data"   =>[],
        "count"  =>0,
        "message" =>trans("crudvel.api.success")
      ]);
    if(!empty($this->getModelCollectionInstance()->id) || $this->getPaginatorDefiner()->getForceSingleItemPagination())
      $this->paginateData=$this->getModelBuilderInstance()->first();

    if(!$this->paginateData && !$this->getPaginatorDefiner()->getSlugedResponse())
      $this->paginateData = $this->getModelBuilderInstance()->get();

    if(!$this->paginateData){
      $keyed = $this->getModelBuilderInstance()->get()->keyBy(function ($item) {
        return Str::slug($item[$this->getPaginatorDefiner()->getSlugField()]);
      });
      $this->paginateData = $keyed->all();
    }
    return $this->getPaginatorDefiner()->apiSuccessResponse([
      "data"    => $this->paginateData,
      "count"   => $this->paginateCount,
      "message" => trans("crudvel.api.success")
    ]);
  }


  /**
   * Responde una peticion http de forma paginada de acuerdo a la combinacion de parametros mandados
   *
   * @param model   prefilteredModel  Modelo con filtros default para el servicio a paginar
   *
   * @author Benomas benomas@gmail.com
   * @date   2017-05-08
   * @return respuesta http paginada
   */
  public function paginatedResponse() {
    $this->processPaginatedResponse();
    return $this->paginateResponder();
  }

  public function noPaginatedResponse(){
    //add joins
    //$this->getPaginatorDefiner()->joins();

    $response = $this->getModelCollectionInstance();
    $selectables = $this->getSelectables();
    if($selectables && count($selectables)){
      $this->selectQuery = $selectables;
      $this->fixSelectables();
      if($this->getModelCollectionInstance() && $this->getModelCollectionInstance()->id)
        $this->getModelBuilderInstance()->id($this->getModelCollectionInstance()->id);
      $response = $this->getModelBuilderInstance()->select($selectables)->first();
    }

    return $this->getPaginatorDefiner()->apiSuccessResponse($response);
  }

  protected function filter() {
    if(!isset($this->searchObject) || !$this->searchObject)
      $this->searchObject = '';
    foreach ($this->filterQuery as $field=>$filter){
      if(is_array($filter)){
        $this->applyCustomFilter($field,$filter);
        unset($this->filterQuery,$field);
      }
    }
    //default filter
    if(!empty($this->filterQuery))
      $this->getModelBuilderInstance()->where(function($query){
        foreach ($this->filterQuery as $field=>$filter){
          $method=!isset($method)?"where":"orWhere";
          $query->{$method}($field,$this->comparator,"%{$this->searchObject}%");
        }
      });
  }

  public function fixables($property){
    $simpleColumns=$this->$property;
    if(!empty($this->joinables) && count($this->joinables))
      foreach ($this->joinables as $joinableKey => $joinableValue)
        foreach ($simpleColumns as $simpleColumnKey => $simpleColumnValue)
          if(!empty($joinableValue) && $simpleColumnValue===$joinableKey){
            $this->$property[$simpleColumnKey] = $joinableValue." AS ".$joinableKey;
            unset($simpleColumns[$simpleColumnKey]);
          }

    if($simpleColumns && count($simpleColumns)){
      $columns = $this->getPaginatorDefiner()->modelInstanciator(true)->getTableColumns();
      foreach ($simpleColumns as $simpleColumnKey => $simpleColumnValue){
        if (in_array($simpleColumnValue,$columns))
          $this->$property[$simpleColumnKey] = $this->getPaginatorDefiner()->getMainTableName().$simpleColumnValue." AS ".$simpleColumnValue;
        else
          $this->unsolvedColumns[$simpleColumnValue] = $simpleColumnKey;
      }
    }
  }

  /**
   * This function allow to transform simple selectable column to a table.column field, in this way
   * is posible to direct use joinable columns, the model needs to declare de join tables too, maybe * rewriting call_action function for global resource join, or by action for specific join
   *
   * @param simpleColumns   Array of selectable columns
   *
   * @author Benomas benomas@gmail.com
   * @date   2018-04-04
   * @return array with fixed seletable columns
   */
  public function fixSelectables(){
    $this->fixables('selectQuery');
  }

  /**
   * This function allow to transform simple filterable column to a table.column field, in this way
   * is posible to direct use joinable columns, the model needs to declare de join tables too, maybe * rewriting call_action function for global resource join, or by action for specific join
   *
   * @param simpleColumns   Array of filterable columns
   *
   * @author Benomas benomas@gmail.com
   * @date   2018-04-04
   * @return array with fixed seletable columns
   */
  public function fixFilterables(){
    $this->fixables('filterQuery');
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
}
