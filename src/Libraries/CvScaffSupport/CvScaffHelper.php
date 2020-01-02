<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CvScaffHelper
{
  use \Crudvel\Traits\CacheTrait;
  use \Crudvel\Traits\CvClosureCommandTrait;
  private $crudvelScaffTreeAbsolutePath;
  private $proyectScaffTreeRelPath='customs/crudvel/Scaff/scaffTree.json';
  private $consoleInstance;
  private $crudvelScaffTree;
  private $proyectScaffTree;
  private $finalScaffTree;

  public function __construct(Command $consoleInstance){
    $this->setCrudvelScaffTreeAbsolutePath(__DIR__.'/scaffTree.json');
    $this->setConsoleInstance($consoleInstance);
  }

//[Getters]
  public function getCrudvelScaffTreeAbsolutePath(){
    return $this->crudvelScaffTreeAbsolutePath??null;
  }

  public function getProyectScaffTreeRelPath(){
    return $this->proyectScaffTreeRelPath??null;
  }

  public function getCrudvelScaffTree(){
    return $this->crudvelScaffTree??[];
  }

  public function getProyectScaffTree(){
    return $this->proyectScaffTree??[];
  }

  public function getFinalScaffTree(){
    return $this->finalScaffTree??[];
  }
//[End Getters]

//[Setters]
  public function setCrudvelScaffTreeAbsolutePath($crudvelScaffTreeAbsolutePath){
    $this->crudvelScaffTreeAbsolutePath=$crudvelScaffTreeAbsolutePath??null;
    return $this;
  }

  public function setProyectScaffTreeRelPath($proyectScaffTreeRelPath){
    $this->proyectScaffTreeRelPath=$proyectScaffTreeRelPath??null;
    return $this;
  }

  private function setCrudvelScaffTree($crudvelScaffTree=null){
    $this->crudvelScaffTree = $crudvelScaffTree??null;
    return $this;
  }

  private function setProyectScaffTree($proyectScaffTree=null){
    $this->proyectScaffTree = $proyectScaffTree??null;
    return $this;
  }

  public function setFinalScaffTree($finalScaffTree=null){
    $this->finalScaffTree = $finalScaffTree??null;
    return $this;
  }
//[End Setters]
//[Stablishers]

  private function stablishCrudvelScaffTree(){
    try{
      $crudvelScaffTree = (array) json_decode(file_get_contents($this->getCrudvelScaffTreeAbsolutePath()),true);
    }catch(\Exception $e){
      throw new \Exception("Error, Unable to load crudvel scaff tree");
    }
    return $this->setCrudvelScaffTree($crudvelScaffTree??null);
  }

  private function stablishProyectScaffTree(){
    try{
      $proyectScaffTree = (array) json_decode(file_get_contents(base_path($this->getProyectScaffTreeRelPath())),true);
    }catch(\Exception $e){
      cvConsoler(cvBrownTC('Warning, Unable to load crudvel scaff tree')."\n");
    }
    return $this->setProyectScaffTree($proyectScaffTree??null);
  }

  public function stablishFinalScaffTree(){
    if(!$this->cvCacheGetProperty('finalScaffTree')){
      $this->stablishCrudvelScaffTree();
      $this->stablishProyectScaffTree();
      $this->cvCacheSetProperty('finalScaffTree',array_replace_recursive($this->getCrudvelScaffTree(), $this->getProyectScaffTree()));
    }
    if(!$finalScaffTree = $this->cvCacheGetProperty('finalScaffTree'))
      throw new \Exception("Error, Unable process/load scaff tree");
    return $this->setFinalScaffTree($finalScaffTree);
  }

  public function dinamicArtisans(){
    $autoCompleter = [];
    $artisans      = [];

    foreach($this->getFinalScaffTree() as  $context=>$contextSubScaffTree){
      foreach($contextSubScaffTree as  $mode=>$modeSubScaffTree){
        foreach($modeSubScaffTree as  $target=>$targetSubScaffTree){
          $callBack = function() use($context,$mode,$target){
            $this->call('cv-scaff',['context'=>$context,'mode'=>$mode,'target'=>$target]);
            return "php artisan cv-scaff $context $mode $target";
          };
          $autoCompleter["$target-$mode-$context"] = "cv-scaff $context $mode $target -{resource}";
          $artisans     ["$target-$mode-$context"] = $callBack;
          $autoCompleter["$target-$context-$mode"] = "cv-scaff $context $mode $target -{resource}";
          $artisans     ["$target-$context-$mode"] = $callBack;
          $autoCompleter["$mode-$target-$context"] = "cv-scaff $context $mode $target -{resource}";
          $artisans     ["$mode-$target-$context"] = $callBack;
          $autoCompleter["$mode-$context-$target"] = "cv-scaff $context $mode $target -{resource}";
          $artisans     ["$mode-$context-$target"] = $callBack;
          $autoCompleter["$context-$mode-$target"] = "cv-scaff $context $mode $target -{resource}";
          $artisans     ["$context-$mode-$target"] = $callBack;
          $autoCompleter["$context-$target-$mode"] = "cv-scaff $context $mode $target -{resource}";
          $artisans     ["$context-$target-$mode"] = $callBack;
        }
      }
    }
    return [$autoCompleter,$artisans];
  }
//[End Stablishers]

  public function cvScaffList(){
    list($autoCompleter,$artisans) = $this->dinamicArtisans();
    $failMessage='';
    do{
      $selection = $this->select(
        "$failMessage\nSelecciona el comando a ejecutar",
        $autoCompleter,
        $autoCompleter[0]??''
      );
      $failMessage = "\n";
    }while(!($artisans[$selection]??null));

    cvConsoler(cvBlueTC("\n".$artisans[$selection]()." was called\n"));
    return $this;
  }
}
