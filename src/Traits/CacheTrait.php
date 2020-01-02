<?php

namespace Crudvel\Traits;

trait CacheTrait
{

  public function setCacheBoots(){
    //setCallback definition example
    $this->cvContext('cvCacheSetCallBack','model_cache_example',function(){
      return 'test';
    });
  }

  public function cvCacheGetProperty($property){
    return $this->cvContext()->setAndGetProperty($property);
  }

  public function cvCacheSetCallBack($property, $callback){
    return $this->cvContext()->setCallBack($property, $callback);
  }

  public function cvCacheHasEngine(){
    return $this->cvContext()->hasEngine();
  }

  public function cvCacheSetProperty($property,$value){
    return $this->cvContext()->setProperty($property,$value);
  }

  private function cvContext(){
    return \App::make('cvCache');
  }

  public function cvSetIncrement($property){
    return $this->cvContext()->setIncrement($property);
  }

  public function cvGetIncrement($property){
    return $this->cvContext()->getIncrement($property);
  }
}
