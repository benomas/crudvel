<?php namespace Crudvel\Libraries;
/**
 *
 *
 * @author Benomas benomas@gmail.com
 * @date   2019-01-12
 */
use Crudvel\Libraries\PathInspectorInterface;
use Crudvel\Libraries\PathRegexInterface;
use Crudvel\Libraries\PathInspectorTrait;
class PathInspector implements PathInspectorInterface
{
  use PathInspectorTrait;
  protected $type;
  protected $path;
  protected $depth                       = 0;
  protected $subDirs                     = [];
  protected $subFiles                    = [];
  protected $lastSegmentSubDirs          = [];
  protected $lastSegmentSubFiles         = [];
  protected $subDirsByDepth              = [];
  protected $subFilesByDepth             = [];
  protected $lastSegmentSubDirsByDepth   = [];
  protected $lastSegmentSubFilessByDepth = [];
  protected $children                    = [];
  protected $pathRegex                   = null;

  public function __construct($path,PathInspectorInterface $parent = null,PathRegexInterface $pathRegex = null){
    if(!file_exists($path))
      return null;

    $this->pathRegex = $pathRegex??$this;
    $this->pathRegex->setSource($this);

    if(is_dir($path))
      $this->type = 'dir';
    else
      $this->type = 'file';
    $this->path   = $path;
    $this->parent = $parent;
    if($this->parent)
      $this->depth = $this->parent->depth + 1;
    $this->scanDir();
  }

  //getters
  public function getType(){
    return $this->type;
  }
  public function getPath(){
    return $this->path;
  }
  public function getDepth(){
    return $this->depth;
  }
  public function getSubDirs(){
    return $this->subDirs;
  }
  public function getSubFiles(){
    return $this->subFiles;
  }
  public function getLastSegmentSubDirs(){
    return $this->lastSegmentSubDirs;
  }
  public function getLastSegmentSubFiles(){
    return $this->lastSegmentSubFiles;
  }
  public function getSubDirsByDepth(){
    return $this->subDirsByDepth;
  }
  public function getSubFilesByDepth(){
    return $this->subFilesByDepth;
  }
  public function getLastSegmentSubDirsByDepth(){
    return $this->lastSegmentSubDirsByDepth;
  }
  public function getLastSegmentSubFilessByDepth(){
    return $this->lastSegmentSubFilessByDepth;
  }
  public function getChildren(){
    return $this->children;
  }
  public function getPathRegex(){
    return $this->pathRegex;
  }

  //setters
  public function setType($type=null){
    $this->type=$type;
  }
  public function setPath($path=null){
    $this->path=$path;
  }
  public function setDepth($depth=null){
    $this->depth=$depth;
  }
  public function setSubDirs($subDirs=null){
    $this->subDirs=$subDirs;
  }
  public function setSubFiles($subFiles=null){
    $this->subFiles=$subFiles;
  }
  public function setLastSegmentSubDirs($lastSegmentSubDirs=null){
    $this->lastSegmentSubDirs=$lastSegmentSubDirs;
  }
  public function setLastSegmentSubFiles($lastSegmentSubFiles=null){
    $this->lastSegmentSubFiles=$lastSegmentSubFiles;
  }
  public function setSubDirsByDepth($subDirsByDepth=null){
    $this->subDirsByDepth=$subDirsByDepth;
  }
  public function setSubFilesByDepth($subFilesByDepth=null){
    $this->subFilesByDepth=$subFilesByDepth;
  }
  public function setLastSegmentSubDirsByDepth($lastSegmentSubDirsByDepth=null){
    $this->lastSegmentSubDirsByDepth=$lastSegmentSubDirsByDepth;
  }
  public function setLastSegmentSubFilessByDepth($lastSegmentSubFilessByDepth=null){
    $this->lastSegmentSubFilessByDepth=$lastSegmentSubFilessByDepth;
  }
  public function setChildren($children=null){
    $this->children=$children;
  }
  public function setPathRegex($pathRegex=null){
    $this->pathRegex=$pathRegex;
  }
}
