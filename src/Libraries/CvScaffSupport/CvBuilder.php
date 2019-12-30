<?php

namespace Crudvel\Libraries\CvScaffSupport;
use \Crudvel\Interfaces\CvScaffInterface;

class CvBuilder extends \Crudvel\Libraries\CvBuilder
{
  public function __construct($cvBuildClass=null){
    $cvBuildClass = $cvBuildClass ?? 'Crudvel\Libraries\CvScaffSupport\CvScaff';
    parent::__construct($cvBuildClass);
  }

  public function setContext($context=null){
    $this->getCvBuildedInstance()->setContext($context??null);
    return $this;
  }

  public function setTarget($target=null){
    $this->getCvBuildedInstance()->setTarget($target??null);
    return $this;
  }

  public function setMode($mode=null){
    $this->getCvBuildedInstance()->setMode($mode??null);
    return $this;
  }

  public function setConsoleInstance($consoleInstance=null){
    $this->getCvBuildedInstance()->setConsoleInstance($consoleInstance??null);
    return $this;
  }

  public function setResource($resource=null){
    $this->getCvBuildedInstance()->setResource($resource??null);
    return $this;
  }
  public function force(){
    $this->getCvBuildedInstance()->force();
    return $this;
  }

  public function setProcessorInstance(CvScaffInterface $processorInstace=null){
    $this->getCvBuildedInstance()->setProcessorInstance($processorInstace??null);
    return $this;
  }
}
