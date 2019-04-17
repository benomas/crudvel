<?php
namespace Crudvel\Libraries\Helpers;

trait FunctionTrait
{
  public function uCProp($uCprop=null){
    $this->uCprop=$uCprop;
    return $this;
  }

  public function uCSort($itemI,$nextItem){
    if($this->uCprop)
      return $itemI[$this->uCprop] === $nextItem[$this->uCprop]?
        0:(($itemI[$this->uCprop] < $nextItem[$this->uCprop]) ? -1 : 1);
    return $itemI === $nextItem?0:(($itemI < $nextItem) ? -1 : 1);
  }
}
