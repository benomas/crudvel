<?php

namespace Crudvel\Libraries\Paginators;
use Crudvel\Libraries\Paginators\CvPaginate;
use DB;

class CvCombinatoryPaginator extends CvBasePaginator implements CvPaginate
{
  protected $comparator        = 'like';
  protected $combinatoryUnions = [];
  protected $depthLimit        = 5;
  protected $numberOfWords     = 0;
  protected $currentUnionDepth = 0;

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

  public function filter() {
    if(!$this->getSearchObject()){
      $this->combinatoryUnions[] = $this->getModelBuilderInstance();
      return $this->getModelBuilderInstance();
    }
    $words = preg_split('/\s+/', $this->getSearchObject());
    //Busqueda por columnas
    foreach($words AS $key=>$word){
      $match = kageBunshinNoJutsu($this->getModelBuilderInstance())->where(DB::raw($this->getDbEngineContainer()->getFilterQueryString()), 'LIKE', "%{$word}%");
      if($match->count())
        $this->combinatoryUnions[] = $match;
      else
        unset($words[$key]);
    }
    $this->numberOfWords = count($words);
    //Busqueda por combinaciones
    if($this->numberOfWords < 20){
      $this->depthLimit = $this->depthLimit - (int) sqrt($this->numberOfWords) + 1;
      $this->depthUnion($words);
    }
    //Busqueda absoluta
    $this->combinatoryUnions[] = kageBunshinNoJutsu($this->getModelBuilderInstance())->where(DB::raw($this->getDbEngineContainer()->getFilterQueryString()), 'LIKE', "%{$this->getSearchObject()}%");
    $this->unions();
    //pdd($this->getModelBuilderInstance()->count());
  }

  protected function depthUnion($words){
    if(count($words)<2)
      return null;

    foreach($words as $key=>$word){
      $clonedModel = kageBunshinNoJutsu($this->getModelBuilderInstance());
      $nextWords = [];
      $method    = null;
      $method=!$method?"where":"orWhere";
      $clonedModel->{$method}(function($query) use($words,$word,$key,$method,&$nextWords){
        foreach($words as $subIndex=>$nextWord){
          if($word === $nextWord && $key === $subIndex)
            continue;
          $nextWords[] = $nextWord;
          $query->where(DB::raw($this->dbEngineContainer->getFilterQueryString()), 'LIKE', "%{$nextWord}%");
        }
        if( ($this->numberOfWords - $this->depthLimit) < count($nextWords) && count($nextWords)>2)
          $this->combinatoryUnions[] = $this->depthUnion($nextWords);
      });
      if($clonedModel->count())
        $this->combinatoryUnions[] = $clonedModel;
    }
  }

  public function unions(){
    $allUnions = null;
    foreach(array_reverse((array) $this->combinatoryUnions) as $partialUnion){
      if(!$partialUnion)
        continue;
      //$partialUnion->select($this->getSelectQuery());
      if(!$allUnions)
        $allUnions = $partialUnion;
      else
        $allUnions->union($partialUnion);
    }
    $this->setModelBuilderInstance($allUnions);
  }
}
