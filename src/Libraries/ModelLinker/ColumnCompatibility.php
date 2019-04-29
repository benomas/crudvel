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

  public function __construct(String $leftModel, String $rightModel, String $leftColumn, String $rightColumn, Int $rightMargin=3){
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
    if($this->lCount($leftCheck) > $this->rCount($rightCheck) + $this->rightMargin || $this->lCount($leftCheck)===0)
      return $this->noCompatibility();
    $this->equals = $this->lCount($leftCheck) === $this->rCount($rightCheck);
    $modelCollection = collect([]);
    $completed       = false;
    $steps           = 0;
    $limit           = 1000;
    $lastMargin      = $this->rightMargin;
    $leftCheck->limit($limit);
    $leftCheck->groupBy($this->leftFixedColumn);
    while(!$completed){
      $leftChunckedData = [];
      $leftCheck->skip($limit * $steps++)->get()->each(function($row) use(&$leftChunckedData){
        $leftChunckedData[]= $row->getOriginal($this->leftColumn);
      });
      if(empty($leftChunckedData) && $completed=true)
        return $this->kindOfCompatibility();
      $rightCheck = $this->rightModel::whereIn($this->rightFixedColumn,$leftChunckedData);

      if($this->rCount($rightCheck)===0)
        return $this->noCompatibility();

      if(count($leftChunckedData) > $this->rCount($rightCheck)){
        if(count($leftChunckedData) - $this->rCount($rightCheck) > $lastMargin)
          return $this->noCompatibility();
        $lastMargin = $lastMargin - count($leftChunckedData) + $this->rCount($rightCheck);
      }
    }
    return $this->kindOfCompatibility();
  }

  private function kindOfCompatibility(){
    return [
      'kindOfCompatibility'=>$this->equals? static::PERFECT_COMPATIBILITY:static::PROBABLE_COMPATIBILITY,'leftCount'=>$this->lCount($this->leftBuilder()),
      'rightCount'=>$this->rCount($this->rightBuilder())
    ];
  }

  private function noCompatibility(){
    return [
      'kindOfCompatibility' => static::UNPROBABLE_COMPATIBILITY,
      'leftCount'           => null,
      'rightCount'          => null
    ];
  }

  private function leftBuilder(){
    return $this->leftModel::select($this->leftFixedColumn)->notNull($this->leftColumn);
  }

  private function rightBuilder(){
    return $this->rightModel::select($this->rightFixedColumn);
  }

  public function lCount($q){
    try{
      return $q->distinctCount($this->leftColumn);
    }
    catch(\Exception $e){
      customLog($this->leftColumn. 'Is invalid for count porpuses');
      return 0;
    }
  }

  public function rCount($q){
    try{
      return $q->distinctCount($this->rightColumn);
    }
    catch(\Exception $e){
      customLog($this->rightColumn. 'Is invalid for count porpuses');
      return 0;
    }
  }
}

