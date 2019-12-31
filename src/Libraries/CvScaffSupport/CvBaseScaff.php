<?php

namespace Crudvel\Libraries\CvScaffSupport;

class CvBaseScaff
{
  use \Crudvel\Traits\CvPatronTrait;
  use \Crudvel\Traits\CvClosureCommandTrait;
  private $consoleInstance;
  private $resource;
  private $scaffParams;
  private $force;

  public function __construct(){
  }

//[Getters]
  public function getResource(){
    return $this->resource??'';
  }

  public function getScaffParams(){
    return $this->scaffParams??[];
  }

  public function getForce(){
    return $this->force??null;
  }

  public function proyectRelatedRootPath(){
    return '""';
  }

  //[End Getters]

  //[Setters]

  public function setResource($resource=null){
    $this->resource = $resource??null;
    return $this;
  }

  public function setScaffParams($scaffParams=null){
    $this->scaffParams = $scaffParams??[];
    return $this;
  }

  public function setForce($force=null){
    $this->force = $force??null;
    return $this;
  }
  //[End Setters]

  //[Stablishers]
  //[End Stablishers]

  public function force(){
    return $this->setForce(true);
  }

  public function isForced(){
    return $this->getForce();
  }
}
