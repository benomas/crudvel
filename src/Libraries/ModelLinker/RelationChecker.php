<?php
namespace Crudvel\Libraries\ModelLinker;
use DB;

class RelationChecker
{
  // Array that contents all relations to check
  protected $relationArray = [];

  public function __construct($relationArray, $leftDestModel, $rightDestModel)
  {
    $this->relationArray = $relationArray;
    $this->rightDestModel = base64_decode($rightDestModel);
    $this->leftDestModel = base64_decode($leftDestModel);
  }

  // toggle direction
  public function toggleDirect($direction){
    return ($direction === 'left')?'right':'left';
  }

  public function buildTpl($rel, $direction){
    $toggleDirection = $this->toggleDirect(lcfirst($direction));
    $funcRel = ($direction === 'left')?'belongsTo':'hasMany';
    $funcName = lcfirst(class_basename(base64_decode($rel['encoded'.ucfirst($toggleDirection).'Model'])));
    $tpl = "\tpublic function " . $funcName. "(){
\t\treturn \$this->".$funcRel."('".$rel[$toggleDirection.'Model']."','" . $rel[$direction.'Column']. "','" . $rel[$toggleDirection.'Column']. "');
\t}";
    return $tpl;
  }

  public function existRelationCode($fileContents, $funcName){
    return preg_match('/public\s+function\s+'.$funcName.'\s*\(\).*/', $fileContents);
  }

  public function writeRelationCodeInFile($rel, $fileContents, $file, $direction){
    $old = $fileContents;
    // build the template to insert in the file
    $tpl = $this->buildTpl($rel, $direction);
    // search and replace inside the file
    $fileContents = preg_replace('/(\n\/\/\[*End Relationships\]*)/', "\n" . $tpl . "$1", $fileContents);
    // insert new lines in file
    file_put_contents($file, $fileContents);
    return $old !== $fileContents;
  }

  public function insertRelationInClass($rel, $direction){
    $toggleDirection = ucfirst($this->toggleDirect(lcfirst($direction)));
    $file = base_path().'/'.base64_decode($rel['encoded'.ucfirst($direction).'TargetPath']);
    // is this relation I = Ignored ?
    if($rel[$direction.'Relation'] === 'I') return [$file, false, 'Ignored'];
    // open the file to edit contents
    $fileContents = file_get_contents($file);
    $funcName = lcfirst(class_basename(base64_decode($rel['encoded'.$toggleDirection.'Model'])));
    $exist = $this->existRelationCode($fileContents, $funcName);
    if($rel[$direction.'Relation'] === 'F'){
      if($exist){
        $fileContents = $this->eraseRelationCode($rel, $direction, $fileContents);
        if(is_null($fileContents)) return [$file,false, 'Null error eraseRelationCode'];
        return [$file, $this->writeRelationCodeInFile($rel, $fileContents, $file, $direction),'Forced ok'];
      }
    }
    if(!$exist) return [$file, $this->writeRelationCodeInFile($rel, $fileContents, $file, $direction)];
    return [$file, false, 'Default no forced'];
  }

  public function eraseRelationCode($rel, $direction, $fileContents){
    $toggleDirection = ucfirst($this->toggleDirect(lcfirst($direction)));
    $funcName = lcfirst(class_basename(base64_decode($rel['encoded'.$toggleDirection.'Model'])));
    customLog("antes",$fileContents);
    $fileContents = preg_replace('/public\s+function\s+'.$funcName.'\s*\(\).*\n.*return.*\n.*}/',"", $fileContents);
    customLog("despues",$fileContents);
    return $fileContents;
  }

  public function checkIfRelationsExistInTraits()
  {
    // Response array to list procced relations
    $response = [];
    // iter all relations
    foreach ($this->relationArray as $rel) {
        array_push($response, $this->insertRelationInClass($rel,'left'));
        array_push($response, $this->insertRelationInClass($rel,'right'));
    }
    return $response;
  }
}
