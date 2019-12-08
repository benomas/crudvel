<?php

namespace Crudvel\Libraries\Paginators;
use Crudvel\Libraries\Paginators\CvPaginate;
use DB;

class CvCombinatoryPaginator extends CvBasePaginator implements CvPaginate
{
  protected $comparator               = 'like';
  protected $combinatoryUnions        = [];
  protected $depthLimit               = 5;
  protected $numberOfWords            = 0;
  protected $currentUnionDepth        = 0;
  protected $preProcessedQueryBuilder = null;
  protected $permutedWords            = [];

  /**
   * Respond to the paginate request with permutation strategy
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
    $this->setPreProcessedQueryBuilder(kageBunshinNoJutsu($this->getModelBuilderInstance()));
    $this->tempQuery();
    //if it is not a filter quary defined
    if(noEmptyArray($this->getFilterQuery()))
      $this->filter();

    $this->setPaginateCount(
      $this->getModelBuilderInstance()->distinctCount($this->getModelReference()->getKeyName(),false)
    );

    $this->getModelBuilderInstance()->groupBy($this->getModelReference()->getKeyName());
    //if limit for que query is defined
    if($this->getLimit()){
      $this->getModelBuilderInstance()->limit($this->getLimit());
      //if a page number to get is defined
      if($this->getPage() && $this->getPaginateCount() >= $this->getLimit() * ($this->getPage()-1))
        $this->getModelBuilderInstance()->skip($this->getLimit() * ($this->getPage()-1));
    }

    if ($this->getOrderBy())
      $this->getModelBuilderInstance()
      ->orderBy('pt_order',$this->getAscending()==1?"ASC":"DESC")
      ->orderBy($this->getOrderBy(),$this->getAscending()==1?"ASC":"DESC");
    if($this->getModelCollectionInstance() && !empty($this->getModelCollectionInstance()->id))
      $this->getModelBuilderInstance()->id($this->getModelCollectionInstance()->id,false);
  }

  public function filter() {
    if(!$this->getSearchObject()){
      $querySql = preg_replace('/^select \* from/','select *,1.0  as pt_order from',$this->getModelBuilderInstance()->toSql());
      $this->getModelBuilderInstance()
        ->setQuery(\DB::table(\DB::raw("($querySql) as cv_pag")))
        ->setBindings($this->getModelBuilderInstance()->getBindings());
      return $this->getModelBuilderInstance();
    }

    $words      = (array) preg_split('/\s+/', $this->getSearchObject());
    if(count($words) > 10)
      $words = array_slice($words,0,10);

    $fixedWords = [];
    //Search by individual column first
    foreach($words AS $key=>$word){
      $match = kageBunshinNoJutsu($this->getModelBuilderInstance())->where(DB::raw($this->getDbEngineContainer()->getFilterQueryString()), 'LIKE', "%{$word}%");
      if($match->count())
        $fixedWords[]=$word;
    }
    $this->setNumberOfWords(count($fixedWords));
    if($this->getNumberOfWords()>1){
      $this->permute($fixedWords);
      if(count($words) === $this->getNumberOfWords())
        array_unshift($this->permutedWords,[$this->getSearchObject()],$fixedWords);
      foreach($this->permutedWords as $position=>$permutedWorks)
        $this->loadLike($permutedWorks,$position);
      foreach($this->permutedWords as $position=>$permutedWorks)
        $this->loadAndLike($permutedWorks,$position);
    }
    else
      $this->loadLike([$this->getSearchObject()],0);
    $this->unions();
  }

  public function permute($fixedWords){
    $this->permutedWords = ptPermutations($fixedWords);
  }


  public function hasMinor($skips,$position){
    return $position > 0;
  }

  public function canBeDecresed($skips,$position){
    if($skips[$position] === 0)
      return false;

    if (!$this->hasMinor($skips,$position))
      return count($skips) === 1;

    return $skips[$position] -1 > $skips[$position-1];
  }

  public function fixSkip(&$skips,$fixedWords){
    $skips[] = 0;
  }

  public function makeLike($words=null){
    return implode("%", (array) $words);
  }

  public function loadLike($words,$position){
    $likeBuilder = kageBunshinNoJutsu($this->getModelBuilderInstance())
      ->where(
        DB::raw($this->getDbEngineContainer()->getFilterQueryString()),
        'LIKE',
        "%{$this->makeLike($words)}%"
      );
    $querySql = preg_replace('/^select \* from/','select *,1.'.$position.'  as pt_order from',$likeBuilder->toSql());
    $likeBuilder
      ->setQuery(\DB::table(\DB::raw("($querySql) as cv_pag"))
      ->setBindings($likeBuilder
      ->getBindings())
    );
    $this->combinatoryUnions[] = $likeBuilder;
  }
  public function loadAndLike($words,$position){
    $andLike = '';
    foreach($words as $work)
      $andLike = "LIKE %$work% AND";
    $andLike = rtrim($andLike, ' AND');

    $likeBuilder = kageBunshinNoJutsu($this->getModelBuilderInstance())
      ->where(
        DB::raw($this->getDbEngineContainer()->getFilterQueryString()),
        'LIKE',
        "$andLike"
      );
    $querySql = preg_replace('/^select \* from/','select *,2.'.$position.'  as pt_order from',$likeBuilder->toSql());
    $likeBuilder
      ->setQuery(\DB::table(\DB::raw("($querySql) as cv_pag"))
      ->setBindings($likeBuilder
      ->getBindings())
    );
    $this->combinatoryUnions[] = $likeBuilder;
  }
  public function unions(){
    $allUnions = null;
    foreach((array) $this->combinatoryUnions as $partialUnion){
      if(!$partialUnion)
        continue;
      if(!$allUnions)
        $allUnions = $partialUnion;
      else
        $allUnions->union($partialUnion);
    }
    //ident all unios as one select

    $allUnions
      ->setQuery(
        \DB::table(\DB::raw('('.$allUnions->toSql().') as cv_pag'))
        ->setBindings($allUnions->getBindings())
      );
    $this->setModelBuilderInstance($allUnions);
  }

  public function getDepthLimit(){
    return $this->depthLimit??null;
  }

  public function setDepthLimit($depthLimit=null){
    $this->depthLimit = $depthLimit;
    return $this;
  }

  public function getNumberOfWords(){
    return $this->numberOfWords??false;
  }

  public function setNumberOfWords($numberOfWords=null){
    $this->numberOfWords = $numberOfWords;
    return $this;
  }

  public function getPreProcessedQueryBuilder(){
    return $this->preProcessedQueryBuilder??false;
  }

  public function setPreProcessedQueryBuilder($preProcessedQueryBuilder=null){
    $this->preProcessedQueryBuilder = $preProcessedQueryBuilder;
    return $this;
  }

}
