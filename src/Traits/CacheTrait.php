<?php

namespace Crudvel\Traits;

trait CacheTrait
{

  public function setCacheBoots(){
    //setCallback definition example
    $this->context('cvCacheSetCallBack','model_cache_example',function(){
      return 'test';
    });
  }

  /*
  public function addContainer(String $containerName=''){
    return CvCache::addContainer($containerName);
  }
  public function removeContainer(String $containerName=''){
    return CvCache::removeContainer($containerName);
  }
  public function getContainer(String $containerName=''){
    return CvCache::getContainer($containerName);
  }
  public function getLastContainer(){
    return CvCache::getLastContainer();
  }
  public function switchContainer(String $containerName=''){
    return CvCache::switchContainer($containerName);
  }
  public function resetContainer(String $property=''){
    return CvCache::resetContainer($property);
  }*/

  public function cvCacheGetProperty($property){
    return $this->context()->setAndGetProperty($property);
  }

  public function cvCacheSetCallBack($property, $callback){
    return $this->context()->setCallBack($property, $callback);
  }

  public function cvCacheHasEngine(){
    return $this->context()->hasEngine();
  }

  public function cvCacheSetProperty($property,$value){
    return $this->context()->setProperty($property,$value);
  }

  private function context(){
    return \App::make('cvCache');
  }

  public function cvSetIncrement($property){
    return $this->context()->setIncrement($property);
  }

  public function cvGetIncrement($property){
    return $this->context()->getIncrement($property);
  }

  public function cvHasCallBack($property){
    return $this->context()->hasCallBack($property);
  }

  public function cvHasProperty($property){
    return $this->context()->hasProperty($property);
  }
}
