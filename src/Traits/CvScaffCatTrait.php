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
    if($resource)
      $resource = 'cat-'.ltrim(fixedSlug($resource),'cat-');
    return $this->setResource($resource??null);
  }
//[End Stablishers]
}
