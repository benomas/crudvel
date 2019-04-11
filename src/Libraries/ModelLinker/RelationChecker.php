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
    // build the template to insert in the file
    $tpl = $this->buildTpl($rel, $direction);
    // customLog($tpl);
    // search and replace inside the file
    $fileContents = preg_replace('/(\n\/\/\[*End Relationships\]*)/', "\n" . $tpl . "$1", $fileContents);
    // insert new lines in file

    // pdd($file, $fileContents);
    file_put_contents($file, $fileContents);
    customLog($fileContents);
  }

  public function insertRelationInClass($rel, $direction){
    $toggleDirection = ucfirst($this->toggleDirect(lcfirst($direction)));
    // is this relation I = Ignored ?
    if($rel[$direction.'Relation'] === 'I') return false;
    $file = base_path().'/'.base64_decode($rel['encoded'.ucfirst($direction).'TargetPath']);
    // open the file to edit contents
    $fileContents = file_get_contents($file);
    $funcName = lcfirst(class_basename(base64_decode($rel['encoded'.$toggleDirection.'Model'])));
    $exist = $this->existRelationCode($fileContents, $funcName);
    if($rel[$direction.'Relation'] === 'F'){
      if($exist) $fileContents = $this->eraseRelationCode($rel, $direction, $fileContents);
    }
    if(!$exist) $this->writeRelationCodeInFile($rel, $fileContents, $file, $direction);
    return true;
  }

  public function eraseRelationCode($rel, $direction, $fileContents){
    $toggleDirection = ucfirst($this->toggleDirect(lcfirst($direction)));
    $funcName = lcfirst(class_basename(base64_decode($rel['encoded'.$toggleDirection.'Model'])));
    $fileContents = preg_replace('/public\s+function\s+'.$funcName.'\s*\(\).*\n.*return.*\n.*}/',"borrada", $fileContents);
    if(is_null($fileContents)) pdd("Error eraseRelation preg_replace", $rel, $fileContents);
    return $fileContents;
  }

  public function checkIfRelationsExistInTraits()
  {
    // iter all relations
    foreach ($this->relationArray as $rel) {
      // check for rightModel (catalogs) exists in left table (details)
      if (!method_exists($rel['leftModel'], lcfirst(class_basename($rel['rightModel'])))) {
        // define relationship for lefttable
        if (!$this->insertRelationInClass($rel,'left')) customLog("No rel inserted: ", $rel);
      }
      // check for leftModel (details) exists in left table (catalogs)
      if (!method_exists($rel['rightModel'], lcfirst(class_basename($rel['leftModel'])))) {
        // define relationship for rightTable
        if (!$this->insertRelationInClass($rel,'right')) customLog("No rel inserted: ", $rel);
      }
    }
      pdd("si se encuentra la relacion");
  }
}
