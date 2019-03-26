<?php namespace Crudvel\Libraries;

class CvCache
{
  protected $containers        = [];
  protected $currentContainer  = null;
  protected $lastContainerName = null;

  public function __construct(String $containerName='global'){
    $this->addContainer($containerName)->switchContainer();
  }

  public function addContainer(String $containerName=''){
    if(empty($containerName))
      return $this;
    $this->containers[$containerName] = new \stdClass;
    $this->containers[$containerName]->data      = [];
    $this->containers[$containerName]->callBacks = [];
    $this->lastContainerName = $containerName;
    return $this;
  }

  public function removeContainer(String $containerName=''){
    if(!empty($this->containers[$containerName]))
      unset($this->containers[$containerName]);
    return $this;
  }

  public function getContainer(String $containerName=''){
    return isset($this->containers[$containerName])?$this->containers[$containerName]:$this->currentContainer;
  }

  public function getLastContainer(){
    return $this->containers[$this->lastContainerName];
  }

  public function switchContainer(String $containerName=''){
    if(empty($containerName)){
      $this->currentContainer = $this->getLastContainer();
      return $this;
    }

    if(!empty($this->containers[$containerName]))
      $this->currentContainer = $this->containers[$containerName];

    return $this;
  }

  public function resetContainer(String $property=''){
    if($this->currentContainer){
      if(!empty($property))
        unset($this->currentContainer->data[$property]);
      else
        $this->currentContainer->data[] = [];
    }
    return $this;
  }

  public function getProperty(String $property=''){
    if(isset($this->currentContainer->data[$property]))
      return $this->currentContainer->data[$property];
    if(isset($this->currentContainer->callBacks[$property]))
      return $this->currentContainer->data[$property] = $this->currentContainer->callBacks[$property]();
    return null;
  }

  public function setCallBack($property, $callBack){
    $this->currentContainer->callBacks[$property] = $callBack;
    return $this;
  }

  public function hasEngine(){
    return count($this->currentContainer->callBacks)>0;
  }
}
