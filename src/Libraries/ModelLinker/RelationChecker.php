<?php
namespace Crudvel\Libraries\ModelLinker;
use DB;

class RelationChecker
{
  // Array that contents all relations to check
  protected $relationArray = [];
  protected $patternRel = '/\n\s*(\/\/\[*End Relationships\]*)/';

  public function __construct(){}

  // toggle direction
  public function toggleDirect($direction){
    return ($direction === 'left')?'right':'left';
  }

  public function buildTplRel($rel, $direction){
    $toggleDirection = $this->toggleDirect(lcfirst($direction));
    $funcRel = ($direction === 'left')?'belongsTo':'hasMany';
    $funcName = lcfirst(class_basename(base64_decode($rel['encoded'.ucfirst($toggleDirection).'Model']))).$rel['prefixed'];
    $tpl = "\tpublic function " . $funcName. "(){
\t\treturn \$this->".$funcRel."('".$rel[$toggleDirection.'Model']."','" . $rel['leftColumn']. "','" . $rel['rightColumn']. "');
\t}\n";
    return $tpl;
  }

  public function insertRelationshipComment($fileContents){
    $fileContents = preg_replace('/(\}$)/', "//[Relationships]\n//[End Relationships]\n"."$1", $fileContents);
    return $fileContents;
  }

  public function existEndRelationComment($fileContents){
    return preg_match($this->patternRel, $fileContents);
  }

  public function existRelationCode($fileContents, $funcName){
    return preg_match('/public\s+function\s+'.$funcName.'\s*\(\).*/', $fileContents);
  }

  public function registerPathsAndRelations($model){
     // instantiate current model to register and set Withs
    return (new $model)->setSelfWiths();
  }

  public function writeRelationCodeInFile($rel, $fileContents, $file, $direction){
    $old = $fileContents;
    // build the template to insert in the file
    $tpl = $this->buildTplRel($rel, $direction);
    if(!$this->existEndRelationComment($fileContents))
      $fileContents = $this->insertRelationshipComment($fileContents);
    // search and replace inside the file
    $fileContents = preg_replace($this->patternRel, "\n" . $tpl . "$1", $fileContents);
    // insert new lines in file
    file_put_contents($file, $fileContents);
    $model = base64_decode($rel['encoded'.ucfirst($direction).'Model']);
    $isRegistered = $this->registerPathsAndRelations($model);
    return ($old !== $fileContents) && $isRegistered;
  }

  public function insertRelationInClass($rel, $direction){
    $toggleDirection = ucfirst($this->toggleDirect(lcfirst($direction)));
    $file = base_path().'/'.base64_decode($rel['encoded'.ucfirst($direction).'TargetPath']);
    // is this relation I = Ignored ?
    if($rel[$direction.'Relation'] === 'I') return ['model'=>$file, 'status'=>false, 'message'=>'Ignored'];
    // open the file to edit contents
    $fileContents = file_get_contents($file);
    $funcName = lcfirst(class_basename(base64_decode($rel['encoded'.$toggleDirection.'Model']))).$rel['prefixed'];
    $exist = $this->existRelationCode($fileContents, $funcName);
    if($rel[$direction.'Relation'] === 'F'){
      if($exist){
        $fileContents = $this->eraseRelationCode($rel, $direction, $fileContents);
        if(is_null($fileContents)) return ['model'=>$file, 'status'=>false, 'message'=>'Null error eraseRelationCode'];
        return [$file, $this->writeRelationCodeInFile($rel, $fileContents, $file, $direction),'Forced ok'];
      }
    }
    if(!$exist) return [$file, $this->writeRelationCodeInFile($rel, $fileContents, $file, $direction)];
    return ['model'=> $file, 'status'=>false, 'message'=>'Default no forced'];
  }

  public function eraseRelationCode($rel, $direction, $fileContents){
    $toggleDirection = ucfirst($this->toggleDirect(lcfirst($direction)));
    $funcName = lcfirst(class_basename(base64_decode($rel['encoded'.$toggleDirection.'Model']))).$rel['prefixed'];
    $fileContents = preg_replace('/public\s+function\s+'.$funcName.'\s*\(\).*\n.*return.*\n.*}/',"", $fileContents);
    return $fileContents;
  }

  public function checkRelations()
  {
    // Response array to list procced relations
    $response = [];
    // iter all relations
    foreach ($this->relationArray as $rel) {
        if(!empty($rel['prefixed'])) $rel['prefixed'] = ucfirst($rel['prefixed']);
        array_push($response, $this->insertRelationInClass($rel,'left'));
        array_push($response, $this->insertRelationInClass($rel,'right'));
    }
    return $response;
  }

  public function setRelations($relationArray, $leftDestModel, $rightDestModel){
    $this->relationArray = $relationArray;
    $this->rightDestModel = base64_decode($rightDestModel);
    $this->leftDestModel = base64_decode($leftDestModel);
  }

  public function setPreviewRelations($rel, $nItems, $offset){
    $this->rel= $rel[0];
    $this->nItems = $nItems;
    $this->offset= $offset;
  }

  public function getMyOwnWiths($model){
    $model = base64_decode($model);
    return (new $model)->myOwnWiths;
  }

  public function previewRelations()
  {
    // baseRelation, ajust to myOwnWiths
    $direction = 'left';
    $toggleDirection = $this->toggleDirect($direction);
    $modelLeft = $this->rel[$direction . 'Model']::groupBy()->limit($this->nItems)->get();
    $funcRel = ($direction === 'left') ? 'belongsTo' : 'hasMany';
    foreach ($modelLeft as $item) {
      $item->leftRelation = $item->$funcRel($this->rel[$toggleDirection . 'Model'], $this->rel[$direction . 'Column'], $this->rel[$toggleDirection . 'Column'])->first();
    }
    $modelRight = $this->rel[$toggleDirection . 'Model']::limit($this->nItems)->get();
    $funcRel = ($direction === 'left') ? 'hasMany' : 'belongsTo';
    foreach ($modelRight as $item) {
      $item->rightRelation = $item->$funcRel($this->rel[$direction . 'Model'], $this->rel[$direction. 'Column'], $this->rel[$toggleDirection. 'Column'])->first();
    }
    return [
      'modelLeft' => $modelLeft,
      'modelRight' => $modelRight
    ];
  }
}
