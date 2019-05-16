<?php

namespace Crudvel\Libraries\ModelLinker;
use Crudvel\Libraries\ModelLinker\ColumnCompatibility;
use DB;

class TableCompatibility
{
  const PERFECT_COMPATIBILITY=1;
  const PROBABLE_COMPATIBILITY=2;
  const UNPROBABLE_COMPATIBILITY=3;
  protected $srcs           = [];
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
  protected $columnsCompatibilities = [];

  public function __construct(String $leftModel = '', String $rightModel = '', String $destLeftModel = '', String $destRightModel = ''){
    if($leftModel!=''){
      $this->leftModel              = $leftModel;
      $this->leftModelInstance      = new $this->leftModel();
      $this->leftColumns            = $this->filterColumns($this->leftModelInstance->columnDefinitions());
    }
    if($rightModel!=''){
      $this->rightModel             = $rightModel;
      $this->rightModelInstance     = new $this->rightModel();
      $this->rightColumns           = $this->filterColumns($this->rightModelInstance->columnDefinitions());
    }
    if($destLeftModel !== ''){
      $this->destLeftModel          = $destLeftModel;
      $this->leftDestModelInstance  = new $this->destLeftModel();
    }
    if($destRightModel !== ''){
      $this->destRightModel         = $destRightModel;
      $this->rightDestModelInstance = new $this->destRightModel();
    }
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
          'totalEquals'              => $compatibilityTest['totalEquals'],
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

    foreach($columnsCompatibility as $key => $row)
      $columnsCompatibility[$key]['orderColumn'] = $row['totalEquals'];

    usort($columnsCompatibility, function ($rowI,$nextRow){
      return uCProp('orderColumn')->uCSort($nextRow,$rowI);
    });
    return collect($columnsCompatibility);
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

  public function filterColumns($columns=[]){
    $filteredColumns = [];
    foreach($columns as $column=>$propertys){
      if(
        !in_array(
          $propertys['type'],
          [
            'boolean',
            'date',
            'dateTime',
            'char',
            //'text',
          ]
        )
      )
      $filteredColumns[] = $column;
    }
    return $filteredColumns;
  }

  public function setSrcs($srcs){
    $this->srcs = $srcs;
    return $this;
  }

  public function bruteForce(){
    foreach($this->srcs as $leftModel){
      $leftModelInstance = new $leftModel();
      $leftColumns       = $this->filterColumns($leftModelInstance->columnDefinitions());
      foreach($this->srcs as $rightModel){
        if($leftModel === $rightModel)
          continue;
        $rightModelInstance     = new $rightModel();
        $rightColumns           = $this->filterColumns($rightModelInstance->columnDefinitions()); $columnsCompatibility=[];
        foreach($leftColumns as $leftColumn){
          foreach($rightColumns as $rightColumn){
            $compatibility = new ColumnCompatibility($leftModel , $rightModel ,$leftColumn,$rightColumn);
            if(($compatibilityTest = $compatibility->setCompatibilityPercentLimit(100)->setRightMargin(5)->check())['kindOfCompatibility']===static::UNPROBABLE_COMPATIBILITY)
              continue;

            $columnsCompatibility[]=[
              'leftBaseNameModel'        => class_basename($leftModel),
              'rightBaseNameModel'       => class_basename($rightModel),
              'leftModel'                => $leftModel,
              'rightModel'               => $rightModel,
              'leftColumn'               => $leftColumn,
              'rightColumn'              => $rightColumn,
              'compatibility'            => $compatibilityTest['kindOfCompatibility'],
              'compatibilityTranslation' => $this->translateCompatibility($compatibilityTest['kindOfCompatibility']),
              'leftCount'                => $compatibilityTest['leftCount'],
              'rightCount'               => $compatibilityTest['rightCount'],
              'totalEquals'              => $compatibilityTest['totalEquals'],
              'encodedLeftModelFilePath' => file_exists(cvClassFile($leftModel))?
                base64_encode(str_replace(base_path().'/','',cvClassFile($leftModel))) : null,
              'encodedRightModelFilePath' => file_exists(cvClassFile($rightModel))?
                base64_encode(str_replace(base_path().'/','',cvClassFile($rightModel))) : null,
            ];
          }
        }

        if(empty($columnsCompatibility))
          continue;

        foreach($columnsCompatibility as $key => $row)
          $columnsCompatibility[$key]['orderColumn'] = $row['totalEquals'];

        usort($columnsCompatibility, function ($rowI,$nextRow){
          return uCProp('orderColumn')->uCSort($nextRow,$rowI);
        });
        echo class_basename($leftModel).' => '.class_basename($rightModel).' - completed. '.count($this->srcs)." left\n";
        
        if(empty($this->columnsCompatibilities[$leftModel]))
          $this->columnsCompatibilities[$leftModel]=[];
        
        $this->columnsCompatibilities[$leftModel][$columnsCompatibility[0]['rightModel']]=$columnsCompatibility;
        /*
        $i=$i??0;
        $i++;
        if($i>3)
          return $this->columnsCompatibilities;
        */
      }
    }
    return $this->columnsCompatibilities;
  }
}
