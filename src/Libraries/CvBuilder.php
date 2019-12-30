<?php

namespace Crudvel\Libraries;

class CvBuilder
{
  private $cvBuildedInstance;
  private $cvBuildClass;
  public function __construct($cvBuildClass=null){
    if($cvBuildClass && class_exists($cvBuildClass)){
      $this->setCvBuildClass($cvBuildClass);
      $this->setCvBuildedInstance(new $cvBuildClass());
    }
  }

  public function build(){
    return $this->getCvBuildedInstance();
  }

  protected function getCvBuildClass(){
    return $this->cvBuildClass??null;
  }

  protected function getCvBuildedInstance(){
    return $this->cvBuildedInstance??null;
  }

  protected function setCvBuildClass($cvBuildClass=null){
    $this->cvBuildClass=$cvBuildClass??null;
    return $this;
  }

  protected function setCvBuildedInstance($cvBuildedInstance=null){
    $this->cvBuildedInstance=$cvBuildedInstance??null;
    return $this;
  }

}
