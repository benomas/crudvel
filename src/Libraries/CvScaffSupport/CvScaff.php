<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;

class CvScaff
{
  private $target;
  private $context;
  private $processorInstance;

  public function __construct(){
  }

  public function getContext(){
    return $this->context??null;
  }

  public function getTarget(){
    return $this->target??null;
  }

  public function getProcessorInstance(){
    return $this->processorInstance??null;
  }

  public function getProcessorClass(){
    return get_class($this->getProcessorInstance());
  }

  public function setContext($context=null){
    $this->context = $context;
    return $this;
  }

  public function setTarget($target=null){
    $this->target = $target;
    return $this;
  }

  public function setConsoleInstance($consoleInstance=null){
    $this->getProcessorInstance()->setConsoleInstance($consoleInstance);
    return $this;
  }

  public function setResource($resource=null){
    $this->getProcessorInstance()->setResource($resource);
    return $this;
  }

  public function setMode($mode=null){
    $this->getProcessorInstance()->setMode($mode);
    return $this;
  }

  public function setProcessorInstance(CvScaffInterface $processorInstance=null){
    $this->processorInstance = $processorInstance??null;
    return $this;
  }

  public function isForced(){
    $this->getProcessorInstance()->getForce();
  }

  public function force(){
    $this->getProcessorInstance()->force();
    return $this;
  }

  private function askAditionalParams(){
    $this->getProcessorInstance()->askAditionalParams();
    return $this;
  }

  private function loadTemplate(){
    $this->getProcessorInstance()->loadTemplate();
    return $this;
  }

  private function fixTemplate(){
    $this->getProcessorInstance()->fixTemplate();
    return $this;
  }

  private function inyectFixedTemplate(){
    $this->getProcessorInstance()->inyectFixedTemplate();
    return $this;
  }

  public function runScaff(){
    $this->loadTemplate()->askAditionalParams()->fixTemplate()->inyectFixedTemplate();
  }
}
