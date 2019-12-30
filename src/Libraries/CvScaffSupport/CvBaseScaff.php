<?php

namespace Crudvel\Libraries\CvScaffSupport;

abstract class CvBaseScaff
{
  use \Crudvel\Traits\CvPatronTrait;
  use \Crudvel\Traits\CvClosureCommandTrait;
  private $consoleInstance;

  public function __construct(){
  }

  public function getResource(){
    return $this->resource??'';
  }

  public function getForce(){
    return $this->force??null;
  }

  public function getExtraParams(){
    return $this->extraParams??null;
  }

  public function setForce($force=null){
    $this->force = $force??null;
    return $this;
  }

  public function setResource($resource=null){
    $this->resource = $resource??null;
    return $this;
  }

  public function setExtraParams($extraParams=null){
    $this->extraParams = $extraParams??null;
    return $this;
  }

  public function force($force=null){
    return $this->setForce($force);
  }

  public function isForced(){
    $this->getForce();
  }
}
