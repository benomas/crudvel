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
    $this->setPreProcessedQueryBuilder(kageBunshinNoJutsu($this->getModelBuilderInstance()));
    $this->tempQuery();
    //si existe un array de columnas a filtrar
    if(noEmptyArray($this->getFilterQuery()))
      $this->filter();
    $this->setPaginateCount($this->getModelBuilderInstance()->count());
    //si se solicita limitar el numero de resultados
    if($this->getLimit()){
      $this->getModelBuilderInstance()->limit($this->getLimit());
      //si se especifica una pagina a regresar
      if($this->getPage() && $this->getPaginateCount() >= $this->getLimit() * ($this->getPage()-1))
        $this->getModelBuilderInstance()->skip($this->getLimit() * ($this->getPage()-1));
    }

    if ($this->getOrderBy())
      $this->getModelBuilderInstance()->orderBy($this->getOrderBy(),$this->getAscending()==1?"ASC":"DESC");

    if($this->getModelCollectionInstance() && !empty($this->getModelCollectionInstance()->id))
      $this->getModelBuilderInstance()->id($this->getModelCollectionInstance()->id,false);
  }

  public function filter() {
    if(!$this->getSearchObject()){
      $this->combinatoryUnions[] = $this->getModelBuilderInstance();
      return $this->getModelBuilderInstance();
    }
    $this->permutedWords[] = [$this->getSearchObject()];

    $words      = (array) preg_split('/\s+/', $this->getSearchObject());
    $fixedWords = [];
    //Busqueda por columnas
    foreach($words AS $key=>$word){
      $match = kageBunshinNoJutsu($this->getModelBuilderInstance())->where(DB::raw($this->getDbEngineContainer()->getFilterQueryString()), 'LIKE', "%{$word}%");
      if($match->count())
        $fixedWords[]=$word;
      else
        jdd($match->count());
    }
    $this->setNumberOfWords(count($fixedWords));
    if($this->getNumberOfWords()>1)
      $this->permutedWords[] = $fixedWords;
    //Busqueda por combinaciones
    if($this->getNumberOfWords()>1)
      $this->permute($fixedWords);
    pdd(count($this->combinatoryUnions));
    if($this->getNumberOfWords() < 20){
      $this->setDepthLimit($this->getDepthLimit() - (int) sqrt($this->getNumberOfWords()) + 1);
      $this->depthUnion($fixedWords);
    }
    pdd('%'.implode("%", $fixedWords).'%');
    //Busqueda absoluta
    ;
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
        if( ($this->numberOfWords - $this->getDepthLimit()) < count($nextWords) && count($nextWords)>2)
          $this->combinatoryUnions[] = $this->depthUnion($nextWords);
      });
      if($clonedModel->count())
        $this->combinatoryUnions[] = $clonedModel;
    }
  }

  public function permute($fixedWords){
    if(count($fixedWords) < 2)
      return null;
    $skips        = [];
    $skips[0]     = count($fixedWords) - 1;
    $cursor       = 0;
    while(count($skips) < count($fixedWords)){
      $nextWords = [];
      foreach($fixedWords as $key=>$word){
        if(!in_array($key,$skips))
          $nextWords[] = $word;
      }
      $this->permutedWords[] = $nextWords;
      if($this->canBeDecresed($skips,$cursor))
        $skips[$cursor]--;
      else{
        $decresed = false;
        if($cursor>0){
          $reversable    = true;
          $currentCursor = $cursor;
          while($reversable){
            while($this->hasMinor($skips,$cursor--)){
              if($this->canBeDecresed($skips,$cursor)){
                $decresed =  true;
                $skips[$cursor]--;
                break;
              }
            }
            if($currentCursor >= count($skips) -1)
              $cursor=0;
            else
              $cursor = $currentCursor +1;
            if(count($this->permutedWords)>9){
              jdd(
                $cursor,
                $currentCursor,
                count($skips) -1,
                $skips,
                $this->permutedWords
              );
            }
          }
        }
        if(!$decresed){
          if($cursor < 0)
            $cursor = 0;
          if($skips[$cursor] > 0){
            $skips[$cursor]--;
            $temp = 1;
            for($i = count($skips)-1 ;$i>0;$i--)
              $skips[$i]=count($fixedWords) - $temp++;
            $cursor=count($skips)-1;
          }else{
            $skips[]=0;
            $temp = 1;
            for($i = count($skips)-1 ;$i>=0;$i--)
              $skips[$i]=count($fixedWords) - $temp++;
          }
        }
      }
    }
    /*
    $skipStart = count($fixedWords)-1;
    $skipRang  = 1;
    $resets    = 1;
    while($skipRang < count($fixedWords)){
      $nextWords = [];
      foreach($fixedWords as $key=>$word){
        if($key < $skipStart || $key >= $skipStart + $skipRang)
          $nextWords[] = $word;
      }
      $this->permutedWords[] = $nextWords;
      if($skipStart === 0){
        $skipStart = count($fixedWords)-1;
        $skipRang++;
        $skipStart-=$resets++;
      }
      else
        $skipStart--;
    }*/
    pdd($this->permutedWords);
    pdd($this->makeLike($nextWords));
    pdd(count($this->combinatoryUnions));
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

  public function loadLike($words){
    $this->combinatoryUnions[] = kageBunshinNoJutsu($this->getModelBuilderInstance())
      ->where(
        DB::raw($this->getDbEngineContainer()->getFilterQueryString()),
        'LIKE',
        "%{$this->makeLike($words)}%"
      );
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
