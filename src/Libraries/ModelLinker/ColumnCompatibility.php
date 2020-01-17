<?php

namespace Crudvel\Libraries\ModelLinker;
use DB;

class ColumnCompatibility
{
  const PERFECT_COMPATIBILITY=1;
  const PROBABLE_COMPATIBILITY=2;
  const UNPROBABLE_COMPATIBILITY=3;
  protected $leftModel;
  protected $rightModel;
  protected $leftModelInstance;
  protected $rightModelInstance;
  protected $leftColumn;
  protected $rightColumn;
  protected $leftFixedColumn;
  protected $rightFixedColumn;
  protected $rightMargin;
  protected $equals;
  protected $totalEquals = 0;
  protected $compatibilityPercentLimit = 75;
  protected $compatibilityTolerance = 4;

  public function __construct(String $leftModel, String $rightModel, String $leftColumn, String $rightColumn, Int $rightMargin=null){
    $this->leftModel          = $leftModel;
    $this->rightModel         = $rightModel;
    $this->leftModelInstance  = new $this->leftModel();
    $this->rightModelInstance = new $this->rightModel();
    $this->leftColumn         = $leftColumn;
    $this->rightColumn        = $rightColumn;
    $this->leftFixedColumn    = $this->leftModelInstance->fixColumnName($leftColumn);
    $this->rightFixedColumn   = $this->rightModelInstance->fixColumnName($rightColumn);
    $this->rightMargin        = $rightMargin;
  }

  public function check(){
    $leftCheck  = $this->leftBuilder();
    $rightCheck = $this->rightBuilder();
    $lCount     = $this->lCount($leftCheck);
    $rCount     = $this->rCount($rightCheck);
    $lastMargin = $this->rightMargin ??
      ($this->compatibilityTolerance + (int) (max($lCount,1) * .005));
    if($lCount > $rCount + $lastMargin || $lCount===0)
      return $this->noCompatibility();
    $this->equals = $lCount === $rCount;
    $steps             = 0;
    $limit             = 900;
    $this->totalEquals = 0;
    $leftCheck->limit($limit);
    $leftCheck->groupBy($this->leftFixedColumn);
    while(1){
      $leftChunckedData = [];
      $leftCheck->skip($limit * $steps++)->get()->each(function($row) use(&$leftChunckedData){
        $leftColumn = $row->getOriginal($this->leftColumn);
        if(!in_array($leftColumn,['','null']))
          $leftChunckedData[] = $row->getOriginal($this->leftColumn);
      });
      if(empty($leftChunckedData))
        return $this->kindOfCompatibility();
      $rightCheck = $this->rightModel::whereIn($this->rightFixedColumn,$leftChunckedData);

      $rCount = $this->rCount($rightCheck);
      $this->totalEquals += $rCount;
      if($rCount===0)
        return $this->kindOfCompatibility();

      if(count($leftChunckedData) > $rCount){
        if(count($leftChunckedData) - $rCount > $lastMargin)
          return $this->kindOfCompatibility();
        $lastMargin = $lastMargin - count($leftChunckedData) + $rCount;
      }
    }
    return $this->kindOfCompatibility();
  }

  private function kindOfCompatibility(){
    if(
      ($lCount = $this->lCount($this->leftBuilder())) > 0 &&
      ($rCount = $this->rCount($this->rightBuilder())) > 0 &&
      $this->totalEquals === 0
    )
      return $this->noCompatibility();
    if(($min = min($lCount,$rCount))===0)
      return $this->noCompatibility();
    if(($min = $min - $this->compatibilityTolerance)<1)
      $min = 1;
    $compatibilityPercent = 100 * $this->totalEquals / $min;
    if($compatibilityPercent < $this->compatibilityPercentLimit)
      return $this->noCompatibility();
    return [
      'kindOfCompatibility'  => $this->equals? static::PERFECT_COMPATIBILITY:static::PROBABLE_COMPATIBILITY,
      'leftCount'            => $lCount,
      'rightCount'           => $rCount,
      'totalEquals'          => $this->totalEquals,
      'compatibilityPercent' => $compatibilityPercent,
    ];
  }

  private function noCompatibility(){
    return [
      'kindOfCompatibility'  => static::UNPROBABLE_COMPATIBILITY,
      'leftCount'            => null,
      'rightCount'           => null,
      'totalEquals'          => 0,
      'compatibilityPercent' => 0,
    ];
  }

  private function leftBuilder(){
    return $this->leftModel::select($this->leftFixedColumn)->notNull($this->leftColumn)->selfFilter();
  }

  private function rightBuilder(){
    return $this->rightModel::select($this->rightFixedColumn)->selfFilter();
  }

  public function lCount($q){
    //return $q->distinctCount($this->leftColumn);
    try{
      return $q->distinctCount($this->leftColumn);
    }
    catch(\Exception $e){
      customLog($this->leftColumn. ' Is invalid for count porpuses, in :'.$this->leftModel);
      return 0;
    }
  }

  public function rCount($q){
    try{
      return $q->distinctCount($this->rightColumn);
    }
    catch(\Exception $e){
      customLog($this->rightColumn. ' Is invalid for count porpuses, in :'.$this->rightModel);
      return 0;
    }
  }

  public function setRightMargin($rightMargin){
    $this->rightMargin = $rightMargin;
    return $this;
  }

  public function setCompatibilityPercentLimit($compatibilityPercentLimit){
    $this->compatibilityPercentLimit = $compatibilityPercentLimit;
    return $this;
  }
}

