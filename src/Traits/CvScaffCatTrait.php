<?php

namespace Crudvel\Traits;

trait CvScaffCatTrait
{
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
  public function stablishResource($resource=null){
    return $this->setResource($this->reCat($resource));
  }
//[End Stablishers]
  public function unCat($resource=null){
    if($resource)
      $resource = preg_replace('/(^cat-)(.+)/','$2',fixedSlug($resource));
    return $resource;
  }
  public function reCat($resource=null){
    return 'cat-'.$this->unCat($resource);
  }
}
