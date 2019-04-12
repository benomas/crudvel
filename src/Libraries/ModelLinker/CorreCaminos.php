<?php
namespace Crudvel\Libraries\ModelLinker;

class CorreCaminos{
  protected $srcs    = [];
  protected $paths   = [];
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
    $this->testPath($currentModel);
  }

  public function testPath($currentModel){
    $path = '';
    foreach($this->srcs as $key=>$model){
      if($model !==$this->rightModel)
        unset($this->srcs[$key]);
      $modelBaseName = class_basename($model);
      $relMethod     = camel_case($modelBaseName);

      if(method_exists($currentModel,$relMethod) && $model::noFilters()->count()){
        if($model===$this->rightModel){
          if($currentModel===$this->leftModel){
            $this->paths[]=$path;
            continue;
          }
          return $path;
        }
        $path .= $relMethod.'.';
        if($subPath = $this->testPath($model))
          $path .= $subPath;
      }
    }
  }

  public function getPaths(){
    return $this->paths;
  }
}
