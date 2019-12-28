<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;

class CvScaff
{
  private $resource;
  private $mode;
  private $template;
  private $processorInstance;

  public function __construct(){
  }

  public function getResource(){
    return $this->resource;
  }

  public function getMode(){
    return $this->mode;
  }

  public function getTemplate(){
    return $this->template;
  }

  public function getProcessorInstance(){
    return $this->processorInstance;
  }

  public function getProcessorClass(){
    return $this->processorClass;
  }

  public function setResource($resource=null){
    $this->resource = $resource??null;
    return $this;
  }

  public function setMode($mode=null){
    $this->mode = $mode??null;
    return $this;
  }

  public function setProcessorInstance(CvScaffInterface $processorInstance=null){
    $this->processorInstance = $processorInstance??null;
    return $this;
  }

  private function setTemplate($template=null){
    $this->template = $template??null;
    return $this;
  }

  private function stablishResource(){
    $this->getProcessorInstance()->stablishResource($this->getResource());
    return $this;
  }

  private function stablishMode(){
    $this->getProcessorInstance()->stablishMode($this->getMode());
    return $this;
  }

  private function loadTemplate(){
    $pretemplate = $this->getProcessorInstance()->loadTemplate();
    //add general logic before set template
    $this->setTemplate($pretemplate);
    return $this;
  }

  private function fixTemplate(){
    $pretemplate = $this->getProcessorInstance()->fixTemplate($this->getTemplate());
    //add general logic before set template
    $this->setTemplate($pretemplate);
    return $this;
  }

  private function inyectFixedTemplate(){
    $this->getProcessorInstance()->inyectFixedTemplate($this->getTemplate());
    return $this;
  }

  public function runScaff(){
    $this->stablishResource()
      ->stablishMode()
      ->loadTemplate()
      ->fixTemplate()
      ->inyectFixedTemplate();
  }
}
