<?php namespace Crudvel\Libraries;

use Crudvel\Libraries\PathInspectorInterface;
trait PathInspectorTrait {

  public function scanDir(){
    if($this->type==='file')
      return false;
    $scan = scandir($this->path);
    foreach ($scan as $dirOrFile) {
      if(in_array($dirOrFile,['.','..']))
        continue;
      $nextPath = $this->path.DIRECTORY_SEPARATOR.$dirOrFile;
      if(is_file($nextPath)){
        $this->subFiles[]            = $nextPath;
        $this->lastSegmentSubFiles[] = $dirOrFile;
        $this->depthFileClasify();
      }
      else{
        $this->subDirs[]            = $nextPath;
        $this->lastSegmentSubDirs[] = $dirOrFile;
        $this->children[]           = new self($nextPath,$this);
        $this->depthDirClasify();
      }
    }
  }

  public function depthDirClasify(){
    if(!count($this->subDirs) || ! ($nextParent = $this->parent) )
      return false;
    do{
      foreach ($this->subDirs as $subDir) {
        $nextParent->lastSegmentSubDirsByDepth[$this->depth] = basename($subDir);
        $nextParent->subDirsByDepth[$this->depth][0] = $subDir;
      }
    }while(($nextParent = $nextParent->parent));
    $this->subDirsByDepth[0] = $this->subDirs;
  }

  public function depthFileClasify(){
    if(!count($this->subFiles) || ! ($nextParent = $this->parent) )
      return false;
    do{
      foreach ($this->subFiles as $subfile) {
        $nextParent->lastSegmentSubFilessByDepth[$this->depth][] = basename($subfile);
        $nextParent->subFilesByDepth[$this->depth][] = $subfile;
      }
    }while(($nextParent = $nextParent->parent));
    $this->subFilesByDepth[0] = $this->subFiles;
  }

  //Todo, make a generic implementation to find with regex trow the PathInspectorTree
  public function finder($patern=null,$dept=null,$property=null){
    return 'Fake method';
  }

  public function setSource(PathInspectorInterface $source){
    return $this->source = $source;
  }
}
