<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Console\Command;

class CvBuilder extends \Crudvel\Libraries\CvBuilder
{
  public function __construct(Command $consoleInstance=null,$cvBuildClass=null){
    parent::__construct();
    $cvBuildClass = $cvBuildClass ?? 'Crudvel\Libraries\CvScaffSupport\CvScaff';
    if(!class_exists($cvBuildClass))
      throw new \Exception("Error, class $cvBuildClass doesnt exist");
    $this->setCvBuildClass($cvBuildClass)
      ->setCvBuildedInstance(new $cvBuildClass($consoleInstance))
      ->stablishFinalScaffTree();
  }

//[Setters]
  public function stablishResource($resource=null){
    $this->getCvBuildedInstance()->stablishResource($resource??null);
    return $this;
  }

  public function setProcessorInstance(CvScaffInterface $processorInstace=null){
    $this->getCvBuildedInstance()->setProcessorInstance($processorInstace??null);
    return $this;
  }
//[End Setters]
//[Stablishers]

  private function stablishFinalScaffTree(){
    $this->getCvBuildedInstance()->stablishFinalScaffTree();
    return $this;
  }

  public function stablishContext($context=null){
    $this->getCvBuildedInstance()->stablishContext($context??null);
    return $this;
  }

  public function stablishTarget($target=null){
    $this->getCvBuildedInstance()->stablishTarget($target??null);
    return $this;
  }

  public function stablishMode($mode=null){
    $this->getCvBuildedInstance()->stablishMode($mode??null);
    return $this;
  }
//[End Stablishers]
  public function force(){
    $this->getCvBuildedInstance()->force();
    return $this;
  }
}
