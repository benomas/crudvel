<?php namespace Crudvel\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cache;

class PreactionResponse extends Exception{
  protected $prematureResponse;

  public function render($request){
    return $this->getPrematureResponse();
  }

  public function getPrematureResponse(){
    return $this->prematureResponse??null;
  }

  public function setPrematureResponse($prematureResponse=null){
    $this->prematureResponse = $prematureResponse??null;

    return $this;
  }
}
