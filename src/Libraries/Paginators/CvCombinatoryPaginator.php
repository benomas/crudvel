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
  protected $searchMode               = 'cv-combinatory-paginator';

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
    //if it is not a filter query defined
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
      $bindings = $this->getModelBuilderInstance()->getBindings();
      $this->getModelBuilderInstance()
        ->setQuery(\DB::table(\DB::raw("($querySql) as {$this->getModelClass()::cvIam()->getTable()}")))
        ->setBindings($bindings);
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
    if($this->getNumberOfWords()>0){
      $this->permute($fixedWords);
      array_unshift($this->permutedWords,$fixedWords);
      $this->loadLike([$this->getSearchObject()],'a.1');
      foreach($this->permutedWords as $position=>$permutedWord){
        if(count($permutedWord) > 1){
          $fixWeight1 = 'b.'.((string)($this->getNumberOfWords() - count($permutedWord))).'.'.$position;
          $fixWeight2 = 'b.'.((string)($this->getNumberOfWords() - count($permutedWord) + 1 )).'.'.$position;
          $this->loadLike($permutedWord,$fixWeight1);
          $this->loadAndLike($permutedWord,$fixWeight2);
        }else{
          $this->loadLike($permutedWord,'c.'.$position);
        }
      }
      if(count($words) === $this->getNumberOfWords())
        array_unshift($this->permutedWords,[$this->getSearchObject()]);
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
    $querySql = preg_replace('/^select \* from/','select *,"'.($position).'" as pt_order from',$likeBuilder->toSql());
    $bindings = $likeBuilder->getBindings();
    $likeBuilder->setQuery(\DB::table(\DB::raw("($querySql) as cv_pag"))->setBindings($bindings));
    $this->combinatoryUnions[] = $likeBuilder;
  }

  public function loadAndLike($words,$position){
    $likeBuilder = kageBunshinNoJutsu($this->getModelBuilderInstance());
    foreach($words as $work)
      $likeBuilder->where(
          DB::raw($this->getDbEngineContainer()->getFilterQueryString()),
          'LIKE',
          "%$work%"
      );
    $querySql = preg_replace('/^select \* from/','select *,"'.($position).'" as pt_order from',$likeBuilder->toSql());
    $bindings = $likeBuilder->getBindings();
    $likeBuilder->setQuery(\DB::table(\DB::raw("($querySql) as cv_pag"))->setBindings($bindings));
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

    $bindings = $allUnions->getBindings();
    $allUnions
      ->setQuery(
        \DB::table(\DB::raw('('.$allUnions->toSql().') as cv_pag'))
        ->setBindings($bindings)
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
