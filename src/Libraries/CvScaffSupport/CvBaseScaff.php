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
  protected $fileExtension;

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

  public function getFileExtension(){
    return $this->fileExtension??null;
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

  public function setFileExtension($fileExtension=null){
    $this->fileExtension = $fileExtension??null;
    return $this;
  }
  //[End Setters]

  //[Stablishers]

  public function stablishResource($resource=null){
    return $this->setResource($resource??null);
  }

  //[End Stablishers]

  public function force(){
    $this->propertyDefiner('force',true);
    return $this->setForce(true);
  }

  public function isForced(){
    return $this->getForce();
  }

  protected function getLastRegexMatch($sourceText,$regex){
    preg_match_all($regex,$sourceText,$matches);
    $matches = $matches??null;
    if(!$matches){
      cvConsoler(cvRedTC('no matches for regex'.$regex)."\n");
      return '';
    }
    return end($matches[0]);
  }
}
