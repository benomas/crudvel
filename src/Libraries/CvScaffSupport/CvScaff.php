<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Foundation\Console\ClosureCommand;

use \Crudvel\Interfaces\CvScaffInterface;

class CvScaff
{
  use \Crudvel\Traits\CacheTrait;
  use \Crudvel\Traits\CvClosureCommandTrait;
  private $crudvelScaffTreeAbsolutePath;
  private $proyectScaffTreeRelPath='crudvel/customs/Scaff/scaffTree.json';
  private $consoleInstance;
  private $crudvelScaffTree;
  private $proyectScaffTree;
  private $finalScaffTree;
  private $context;
  private $mode;
  private $target;
  private $resource;
  private $processorClass;
  private $processorInstance;

  public function __construct(ClosureCommand $consoleInstance){
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

  public function getContext(){
    return $this->context??null;
  }

  public function getMode(){
    return $this->mode??null;
  }

  public function getTarget(){
    return $this->target??null;
  }

  public function getProcessorInstance(){
    return $this->processorInstance??null;
  }

  public function getProcessorClass(){
    return $this->processorClass??get_class($this->getProcessorInstance())??null;
  }

  public function getResource(){
    return $this->resource??null;
  }

  private function getContextSubTree(){
    return $this->getFinalScaffTree()[$this->getContext()]??[];
  }

  private function getModeSubTree(){
    return $this->getContextSubTree()[$this->getMode()]??[];
  }

  private function getTargetSubTree(){
    return $this->getModeSubTree()[$this->getTarget()]??[];
  }

  public function getTargetClass(){
    return $this->getTargetSubTree()['class']??null;
  }

  public function getTreePath(){
    $context = $this->getContext()??'';
    $mode    = $this->getMode()??'';
    $target  = $this->getTarget()??'';
    return "context: $context, mode: $mode, tarjet:$target";
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

  public function setContext($context=null){
    $this->context = $context;
    return $this;
  }

  public function setMode($mode=null){
    $this->mode = $mode;
    return $this;
  }

  public function setTarget($target=null){
    $this->target = $target;
    return $this;
  }

  public function setProcessorClass($processorClass=null){
    $this->processorClass = $processorClass??null;
    return $this;
  }

  public function setProcessorInstance(CvScaffInterface $processorInstance=null){
    $this->processorInstance = $processorInstance??null;
    return $this;
  }

  public function setResource($resource=null){
    $this->resource = $resource??null;
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

  public function stablishContext($context=null){
    $finalScaffTree = $this->getFinalScaffTree();
    $contexts       = (array) array_keys($finalScaffTree);
    if(!count($contexts))
      throw new \Exception("Error, scaff tree havent context defined");

    while(($finalScaffTree[$context]??null)===null)
      $context = $this->select('context is not correctly defined, set it again',$contexts,$contexts[0]);

    return $this->setContext($context);
  }

  public function stablishMode($mode=null){
    if(!$context = $this->getContext())
      $context = $this->stablishContext($context)->getContext();

    $contextSubTree = $this->getContextSubTree();
    $modes          = (array) array_keys($contextSubTree);
    if(!count($modes))
      throw new \Exception("Error, scaff tree havent modes defined in $context branch");

    while(($contextSubTree[$mode]??null)===null)
      $mode = $this->select('mode is not correctly defined, set it again',$modes,$modes[0]);
    return $this->setMode($mode);
  }

  public function stablishTarget($target=null){
    if(!$mode= $this->getMode())
      $mode= $this->stablishMode($mode)->getMode();

    $modeSubTree = $this->getModeSubTree();
    $targets     = (array) array_keys($modeSubTree);
    if(!count($targets))
      throw new \Exception("Error, scaff tree havent targets defined in $mode branch");

    while(($modeSubTree[$target]??null)===null)
      $target = $this->select('target is not correctly defined, set it again',$targets,$targets[0]);
    return $this->setTarget($target);
  }

  public function stablishProcessorInstance(){
    if($this->getProcessorInstance())
      return $this;
    $targetClass  = $this->setProcessorClass($this->getTargetClass())->getProcessorClass();
    if(!$targetClass || !class_exists($targetClass))
      throw new \Exception("Error, tarjet class $targetClass is not correctly defined in scaff tree at {$this->getTreePath()}");
    return $this->setProcessorInstance(new $targetClass());
  }

//[End Stablishers]

  public function inyectConsoleInstance(){
    $this->getProcessorInstance()->setConsoleInstance($this->getConsoleInstance());
    return $this;
  }

  public function isForced(){
    if(!$this->getProcessorInstance())
      $this->stablishProcessorInstance();
    return $this->getProcessorInstance()->getForce();
  }

  public function force(){
    if(!$this->getProcessorInstance())
      $this->stablishProcessorInstance();
    $this->getProcessorInstance()->force();
    return $this;
  }

  public function runScaff(){
    $this->stablishProcessorInstance()
      ->inyectConsoleInstance()
      ->getProcessorInstance()
      ->setResource($this->getResource())
      ->setScaffParams($this->getTargetSubTree())
      ->scaff();
  }
}
