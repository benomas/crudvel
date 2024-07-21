<?php namespace Crudvel\Exceptions;

use Exception;

class PreactionResponse extends Exception{
  protected $prematureResponse;

  public function report(){
  }

  public function render($request){
    return $this->getPrematureResponse();
  }

  public function getPrematureResponse(){
    return $this->prematureResponse??null;
  }

  public function setPrematureResponse($prematureResponse=null): static {
    $this->prematureResponse = $prematureResponse??null;

    return $this;
  }
}
