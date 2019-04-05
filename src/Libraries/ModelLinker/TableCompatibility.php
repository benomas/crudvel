<?php

namespace Crudvel\Libraries\ModelLinker;
use Crudvel\Libraries\ModelLinker\ColumnCompatibility;
use DB;

class TableCompatibility
{
  const PERFECT_COMPATIBILITY=1;
  const PROBABLE_COMPATIBILITY=2;
  const UNPROBABLE_COMPATIBILITY=3;
  protected $leftModel;
  protected $rightModel;
  protected $leftModelInstance;
  protected $rightModelInstance;
  protected $leftColumns;
  protected $rightColumns;
  protected $rightMargin;
  protected $equals;
  protected $columnsCompatibility = [];

  public function __construct(String $leftModel, String $rightModel){
    $this->leftModel          = $leftModel;
    $this->rightModel         = $rightModel;
    $this->leftModelInstance  = new $this->leftModel();
    $this->rightModelInstance = new $this->rightModel();
    $this->leftColumns = $this->leftModelInstance->getTableColumns();
    $this->rightColumns = $this->rightModelInstance->getTableColumns();
  }

  public function setColumns (String $leftColumn, String $rightColumn){
    $this->leftColumn         = $leftColumn;
    $this->rightColumn        = $rightColumn;
    $this->leftFixedColumn    = $this->leftModelInstance->fixColumnName($leftColumn);
    $this->rightFixedColumn   = $this->rightModelInstance->fixColumnName($rightColumn);
  }

  public function check(){
    foreach($this->leftColumns as $leftColumn){
      foreach($this->rightColumns as $rightColumn){
        $compatibility = new ColumnCompatibility($this->leftModel , $this->rightModel ,$leftColumn,$rightColumn);
        if(($compatibilityTest = $compatibility->check())['kindOfCompatibility']===static::UNPROBABLE_COMPATIBILITY)
          continue;
        $columnsCompatibility[]=[
          'leftModel'=>$this->leftModel,
          'rightModel'=>$this->rightModel,
          'leftColumn'=>$leftColumn,
          'rightColumn'=>$rightColumn,
          'compatibility'=>$compatibilityTest['kindOfCompatibility'],
          'compatibilityTranslation'=>$this->translateCompatibility($compatibilityTest['kindOfCompatibility']),
          'leftCount'=>$compatibilityTest['leftCount'],
          'rightCount'=>$compatibilityTest['rightCount'],
        ];
      }
    }
    return collect($columnsCompatibility)->sortBy('compatibility');
  }

  private function kindOfCompatibility(){
    return $this->equals? static::PERFECT_COMPATIBILITY:static::PROBABLE_COMPATIBILITY;
  }

  private function leftBuilder(){
    return $this->leftModel::select($this->leftFixedColumn)->notNull($this->leftColumn);
  }

  private function rightBuilder(){
    return $this->rightModel::select($this->rightFixedColumn);
  }

  public function lCount($q){
    return $q->distinctCount($this->leftFixedColumn);
  }

  public function rCount($q){
    return $q->distinctCount($this->rightFixedColumn);
  }

  public function translateCompatibility($compatibility){
    if($compatibility===static::PERFECT_COMPATIBILITY)
      return 'Compatibilidad perfecta';
    if($compatibility===static::PROBABLE_COMPATIBILITY)
      return 'Compatibilidad probable';
    if($compatibility===static::UNPROBABLE_COMPATIBILITY)
      return 'Compatibilidad inprobable';
  }
}

