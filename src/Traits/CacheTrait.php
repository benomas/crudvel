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
}
