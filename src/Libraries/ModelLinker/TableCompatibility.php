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
  protected $leftToRight;
  protected $rightToLeft;
  protected $rightMargin;
  protected $equals;
  protected $columnsCompatibility = [];

  public function __construct(String $leftModel, String $rightModel, String $destLeftModel, String $destRightModel){
    $this->leftModel              = $leftModel;
    $this->rightModel             = $rightModel;
    $this->leftModelInstance      = new $this->leftModel();
    $this->rightModelInstance     = new $this->rightModel();
    $this->leftColumns            = $this->leftModelInstance->getTableColumns();
    $this->rightColumns           = $this->rightModelInstance->getTableColumns();
    $this->destLeftModel          = $destLeftModel;
    $this->destRightModel         = $destRightModel;
    $this->leftDestModelInstance  = new $this->destLeftModel();
    $this->rightDestModelInstance = new $this->destRightModel();
  }

  public function setColumns (String $leftColumn, String $rightColumn){
    $this->leftColumn         = $leftColumn;
    $this->rightColumn        = $rightColumn;
    $this->leftFixedColumn    = $this->leftModelInstance->fixColumnName($leftColumn);
    $this->rightFixedColumn   = $this->rightModelInstance->fixColumnName($rightColumn);
  }

  public function check(){
    $columnsCompatibility=[];
    foreach($this->leftColumns as $leftColumn){
      foreach($this->rightColumns as $rightColumn){
        $compatibility = new ColumnCompatibility($this->leftModel , $this->rightModel ,$leftColumn,$rightColumn);
        if(($compatibilityTest = $compatibility->check())['kindOfCompatibility']===static::UNPROBABLE_COMPATIBILITY)
          continue;

        $modelKind                 = preg_match('/Catalogos/',$this->destLeftModel, $matches)?'Catalogos':"Tbls";
        $encodedLeftTraitFilePath  = 'app/Traits/'.strtolower($modelKind).'/'.class_basename($this->destLeftModel).'Trait.php';
        $modelKind                 = preg_match('/Catalogos/',$this->destRightModel, $matches)?'Catalogos':"Tbls";
        $encodedRightTraitFilePath = 'app/Traits/'.strtolower($modelKind).'/'.class_basename($this->destRightModel).'Trait.php';
        $columnsCompatibility[]=[
          'leftBaseNameModel'        => class_basename($this->leftModel),
          'rightBaseNameModel'       => class_basename($this->rightModel),
          'leftModel'                => $this->leftModel,
          'rightModel'               => $this->rightModel,
          'leftColumn'               => $leftColumn,
          'rightColumn'              => $rightColumn,
          'compatibility'            => $compatibilityTest['kindOfCompatibility'],
          'compatibilityTranslation' => $this->translateCompatibility($compatibilityTest['kindOfCompatibility']),
          'leftCount'                => $compatibilityTest['leftCount'],
          'rightCount'               => $compatibilityTest['rightCount'],
          'encodedLeftTraitFilePath' => file_exists(base_path($encodedLeftTraitFilePath))?
            base64_encode($encodedLeftTraitFilePath) : null,
          'encodedRightTraitFilePath' => file_exists(base_path($encodedRightTraitFilePath))?
            base64_encode($encodedRightTraitFilePath) : null,
          'encodedLeftModelFilePath' => file_exists(cvClassFile($this->leftModel))?
            base64_encode(str_replace(base_path().'/','',cvClassFile($this->leftModel))) : null,
          'encodedRightModelFilePath' => file_exists(cvClassFile($this->rightModel))?
            base64_encode(str_replace(base_path().'/','',cvClassFile($this->rightModel))) : null,
        ];
      }
    }
    $columnsCompatibility = collect($columnsCompatibility);
    $columnsCompatibility = $columnsCompatibility->count()?$columnsCompatibility->sortBy('compatibility'):$columnsCompatibility;
    $fixedColumnsCompatibilityIndex = [];
    foreach($columnsCompatibility as $key=>$compatibility)
      $fixedColumnsCompatibilityIndex[] = $compatibility;
    return collect($fixedColumnsCompatibilityIndex);
  }

  public function crossRelated(){
    $this->leftToRight = method_exists($this->leftModelInstance,camel_case(class_basename($this->rightModel)));
    $this->rightToLeft = method_exists($this->rightModelInstance,camel_case(class_basename($this->leftModel)));
    return [
      'leftToRight' => $this->leftToRight,
      'rightToLeft' => $this->rightToLeft,
    ];
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

