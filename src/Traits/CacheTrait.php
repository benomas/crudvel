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
    if(isset($GLOBALS['model_cache_'.$property]))
      return $GLOBALS['model_cache_'.$property];
    if(isset($this->cacheBoots[$property]))
      return $GLOBALS['model_cache_'.$property] = $this->cacheBoots[$property]();
    return null;
  }
}
