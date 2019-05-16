<?php
namespace Crudvel\Libraries\ModelLinker;

class CorreCaminos{
  protected $srcs           = [];
  protected $paths          = [];
  protected $collectionPath = [];
  protected $leftModel;
  protected $leftModelInstance;
  protected $rightModel;
  protected $rightModelInstance;

  public function __construct($leftModel,$rightModel){
    $this->leftModel           = $leftModel;
    $this->rightModel          = $rightModel;
    $this->leftModelInstance   = new $leftModel();
    $this->rightModelInstance  = new $rightModel();
    $this->srcs                = $this->leftModelInstance->selfSrcs();
  }

  public function checkPaths(){
    $currentModel = $this->leftModel;
    $this->testPath($currentModel,$this->srcs);
    $this->fixDots();
    return $this;
  }

  public function testPath($currentModel,$srcs){
    $path = '';
    foreach($srcs as $key=>$model){
      if(in_array($model,[$this->leftModel,$currentModel])){
        unset($srcs[$key]);
        continue;
      }
      $modelBaseName = class_basename($model);
      $relMethod     = camel_case($modelBaseName);
      if(method_exists($currentModel,$relMethod) && $model::noFilters()->count()){
        unset($srcs[$key]);
        $path = $relMethod.'.';
        //if target was found
        if($model===$this->rightModel){
          //if currentModel is different than origin model
          if($currentModel!==$this->leftModel)
            return $path;
          $this->paths[]=$path;
          continue;
        }
        $subPath = $this->testPath($model,$srcs);
        if($subPath && $subPath !==''){
          $path .= $subPath;
          if($currentModel===$this->leftModel && !in_array($path,$this->paths)){
            $this->paths[]=$path;
          }
          else
            return $path;
        }
      }
    }
  }

  public function getPaths(){
    return $this->paths;
  }

  public function getCollectionPath(){
    return $this->collectionPath;
  }

  public function fixDots(){
    foreach($this->paths as $pathsIndex => $pathsValue)
      $this->paths[$pathsIndex] = rtrim($pathsValue,'.');

    $this->collectionPath = collect($this->paths)->sort(function($item,$nextItem){
      if ($item == $nextItem)
        return 0;
      return
        ((count(explode('.',$item)).'.'.strlen($item)) <
        (count(explode('.',$nextItem)).'.'.strlen($nextItem))) ?
          -1 : 1;
    });
    return $this;
  }
}
