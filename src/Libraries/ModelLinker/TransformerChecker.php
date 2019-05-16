<?php
namespace Crudvel\Libraries\ModelLinker;
use DB;

class TransformerChecker
{
  protected $accessors;
  protected $patternTrans = '/\n\s*(\/\/\[*End Transformers\]*)/';

  public function __construct(){}

  // toggle direction
  public function toggleDirect($direction){
    return ($direction === 'left')?'right':'left';
  }

  public function identCallBack($callBackCode){
    $split = preg_split('/\n/', $callBackCode);
    $result  = [];
    foreach($split as $ln => $line){
      if($ln > 0){
        $identSize = strlen($line) - strlen(ltrim($line));
        $line = "\t\t\t".str_repeat("\t",$identSize).ltrim($line);
      }
        $result[] = $line;
    }
    return implode("\n",$result);
  }

  public function buildTplTrans($acc){
    // define functionDef-inition for all get.*Attribute
    $funcDef ='  public function get'.ucfirst(camel_case($acc['destColumn'])).'Attribute(){';
    if(isset($acc['postfixed'])) $acc['postfixed'] = ucfirst($acc['postfixed']);
    // verify accesorType
    switch($acc['accessorType']){
      case 'simple':
        return $funcDef.'
    return $this->attributes[\''.$acc['attributeName'].'\']??null;
  }';
      break;

      case 'complex':
        $acc['callBack'] = $this->identCallBack($acc['callBack']);
        return $funcDef.'
    return $this->accessorInterceptor(
      '.$acc['callBack'].'
    ,$this->attributes[\''.$acc['attributeName'].'\']??null);
  //endCallback
  }';
      break;

      case 'direct':
      if(!isset($acc['postfixed']))
        return $funcDef.'
    return $this->relationResponse(\''.$acc['relatedDestModel'].'\');
  }';
        return $funcDef.'
    return $this->relationResponse(\''.$acc['relatedDestModel'].'\', $this->'.$acc['postfixed'].');
  }';
      break;

      case 'direct-custom-column':
      if(!isset($acc['postfixed']))
        return $funcDef.'
    return $this->relationResponse(\''.$acc['relatedDestModel'].'\', null, \''.$acc['relatedColumnName'].'\');
  }';
        return $funcDef.'
    return $this->relationResponse(\''.$acc['relatedDestModel'].'\', $this->'.$acc['postfixed'].', \''.$acc['relatedColumnName'].'\');
  }';
      break;

      case 'recursive':
        return $funcDef.'
    return $this->relationResponse(\''.$acc['relatedDestModel'].'\', \''.$acc['path'].'\');
  }';
      break;

      case 'recursive-custom-column':
        return $funcDef.'
    return $this->relationResponse(\''.$acc['relatedDestModel'].'\', \''.$acc['path'].'\',\''.$acc['relatedColumnName'].'\');
  }';
      break;

      case 'return-null':
        return $funcDef.'
    return null;
  }';
    }
  }

  public function writeTransformerCodeInFile($acc, $fileContents, $file){
    $old = $fileContents;
    // build the template to insert in the file
    $tpl = $this->buildTplTrans($acc);
    if(!$this->existEndTransformerComment($fileContents))
      $fileContents = $this->insertTransformerComment($fileContents);
    // search and replace inside the file
    $fileContents = preg_replace($this->patternTrans, "\n" . $tpl . "\n$1", $fileContents);
    // insert new lines in file
    file_put_contents($file, $fileContents);
    return ($old !== $fileContents);
  }

  public function insertTransformerComment($fileContents){
    $fileContents = preg_replace('/(\}$)/',"//[Transformers]\n//[End Transformers]\n".'$1', $fileContents);
    return $fileContents;
  }

  public function existEndTransformerComment($fileContents){
    return preg_match($this->patternTrans, $fileContents);
  }

  public function existTransformerCode($fileContents, $funcName){
    return preg_match('/public\s+function\s+get'.$funcName.'Attribute\s*\(\).*/', $fileContents);
  }

  public function setAccessors($accessors){
    $this->accessorsArray = $accessors;
  }

  public function eraseSpecificTransformerCode($model, $accessorName){
    $model        = base64_decode($model);
    $file         = cvClassFile($model);
    $fileContents = file_get_contents($file);
    $fileContents = $this->eraseTransformerCode($fileContents, studly_case($accessorName));
    file_put_contents($file, $fileContents);
    return ['model' => $model, 'status' => true, 'message' => 'Transformer code removed ok.'];
  }

  public function eraseTransformerCode($fileContents, $funcName){
    $fileContents = preg_replace('/public\s+function\s+get'.$funcName.'Attribute\s*\(\).*\n.*return.*\n.*}/',"", $fileContents);
    $fileContents = preg_replace('/public\s+function\s+get'.$funcName.'Attribute.*(\n|.)*\/\/endCallback\n.*\}/',"", $fileContents);
    return $fileContents;
  }

  public function insertTransformerInClass($acc){
    $file = cvClassFile($acc['srcModel']);
    $fileContents = file_get_contents($file);
    // pdd($acc['accessorType']=='complex', empty($acc['callBack']), $this->evalCallBack($acc['callBack']));
    if($acc['accessorType'] == 'complex' && (empty($acc['callBack']) || !$this->evalCallBack($acc['callBack']))){
        return ['model' => $acc['srcModel'], 'status' => false, 'message' => 'Error validation code.'];
    }
    if($acc['mode'] === 'I') return ['model'=>$acc['srcModel'], 'status'=>false, 'message'=>'Ignored.'];
    if($this->existTransformerCode($fileContents, ucfirst(camel_case($acc['destColumn'])))){
      if($acc['mode']==='F'){
        $fileContents = $this->eraseTransformerCode($fileContents, ucfirst(camel_case($acc['destColumn'])));
      }else{
        return ['model' => $acc['srcModel'], 'status' => false, 'message' => 'Transformer code already exist, force it to overwrite.'];
      }
    }
    if($this->writeTransformerCodeInFile($acc, $fileContents, $file))
      return ['model' => $acc['srcModel'], 'status' => true, 'message' => 'Transformer code written ok.'];
    return ['model' => $acc['srcModel'], 'status' => false, 'message' => 'Error writting transformer code.'];
  }

  public function checkTransformers(){
    $response = [];
    // iter models for transformers
    foreach ($this->accessorsArray as $acc){
      $acc['srcModel'] = base64_decode($acc['srcModel']);
      if(isset($acc['callBack'])) $acc['callBack'] = base64_decode($acc['callBack']);
      array_push($response, $this->insertTransformerInClass($acc));
    }
    return $response;
  }

  public function getAllModelAccessors($model){
    //New Code, calculate  all the accesors including acceros by herence
    $methods    = get_class_methods($model);
    $attributes = [];
    foreach($methods as $key=>$method){
      if(preg_match('/get(.+)Attribute$/',$method,$match) && $match[1]??null)
        $attributes[] = snake_case($match[1]);
    }
    return ['model' => $model, 'existAccessors' => (count($attributes) > 0), 'message' => 'ok', 'accessorsArray' => (count($attributes) > 0) ? $attributes : 'No Accessors found'];

    //Original Code to calculate accessors based on the model file definition
    $fileContents = file_get_contents(cvClassFile($model));
    if(!$this->existEndTransformerComment($fileContents))
      return ['model' => $model, 'existAccessors' => false, 'message' => '\/\/[End Transformers NOT FOUND', 'accessorsArray' => []];
    $match = [];
    preg_match_all('/function get(.*)Attribute.*/', $fileContents, $match);
    foreach ($match[1] as $key => $value) $match[1][$key] = snake_case($value);
    return ['model' => $model, 'existAccessors' => (count($match[1]) > 0), 'message' => 'ok', 'accessorsArray' => (count($match) > 0) ? $match[1] : 'No Accessors found'];
  }

  public function evalCallBack($callBackCode){
    try {
      eval($callBackCode.';');
    } catch (\Throwable $th) {
      return false;
    }
    return true;
  }
}
