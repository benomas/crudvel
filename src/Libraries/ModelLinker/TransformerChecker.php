<?php
namespace Crudvel\Libraries\ModelLinker;
use DB;

class TransformerChecker
{
  protected $patternTrans = '/(\n\s*\/\/\[*End Transformers\]*)/';

  public function __construct(){}

  // toggle direction
  public function toggleDirect($direction){
    return ($direction === 'left')?'right':'left';
  }

  public function buildTplTrans($acc){
    // verify accesorType
    switch($acc['accesorType']){
      case 'simple':
        return 'return $this->attributes[\''.$acc['destColumn'].'\'];';
      break;

      case 'complex':
        return 'return function() { return }();';
      break;

      case 'direct':
        return 'return $this->relationResponse('.$acc['relatedDestModel'].');';
        // return 'return function() { return ucfirst($this->attributes[\''.$acc['destColumn'].'\'])}();';
      break;
    }
  }

  public function insertTransformerComment($fileContents){
    $fileContents = preg_replace('/(\}$)/', "\n//\[Transformers\]\n//\[End Transformers\]\n"."$1", $fileContents);
    return $fileContents;
  }

  public function existEndTransformerComment($fileContents){
    return preg_match($this->patternTrans, $fileContents);
  }

  public function setCheckIfRelationsExist(){
    return true;
  }

  public function checkIfTransformersExist(){
    // entry
    return true;
  }

  public function getAllModelAccessors($model){
    $fileContents = file_get_contents(cvClassFile($model));
    if(!$this->existEndTransformerComment($fileContents))
      return ['model'=>$model, 'existAccessors'=>false, 'message'=>'\/\/[End Transformers NOT FOUND', 'accessorsArray'=>[]];
    $match = [];
    preg_match_all('/function get(.*)Attribute.*/', $fileContents, $match);
    foreach($match[1] as $key => $value) $match[1][$key] = snake_case($value);
    return ['model'=>$model,'existAccessors'=>(count($match[1])>0), 'message'=>'ok', 'accessorsArray'=>(count($match)>0)?$match[1]:'No Accessors found'];
  }
}
