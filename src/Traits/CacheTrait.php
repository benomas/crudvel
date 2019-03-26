<?php

namespace Crudvel\Traits;

trait CacheTrait
{

  public function setCacheBoots(){
    $this->cacheBoots['model_cache_example'] = function(){
      return 'test';
    };
  }

  public function getCacheData($property){
    /*
    if(isset($GLOBALS['model_cache_'.get_class($this).$property]))
      return $GLOBALS['model_cache_'.get_class($this).$property];
    if($callBackResponse = $this->getCallBack($property))
      return $callBackResponse;
      */
    if(isset($GLOBALS['model_cache_'.$property]))
      return $GLOBALS['model_cache_'.$property];
    if(isset($this->cacheBoots[$property]))
      return $GLOBALS['model_cache_'.$property] = $this->cacheBoots[$property]();
    return null;
  }

  public function setCallBack($property,$callBack){
    $GLOBALS['model_callback_'.get_class($this).$property]=$callBack;
  }

  public function getCallBack($property){
    if(
      isset($GLOBALS['model_callback_'.get_class($this).$property]) &&
      is_callable($GLOBALS['model_callback_'.get_class($this).$property])
    )
      return $GLOBALS['model_cache_'.get_class($this).$property] = $GLOBALS['model_callback_'.get_class($this).$property]();
    return null;
  }

  public function setInstanceReady(){
    $GLOBALS['model_cache_'.get_class($this).'-ready']=true;
  }

  public function instanceIsReady(){
    return
      isset($GLOBALS['model_cache_'.get_class($this).'-ready']) &&
      $GLOBALS['model_cache_'.get_class($this).'-ready'];
  }
}
