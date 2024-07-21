<?php namespace Crudvel\Exceptions;

use Exception;

class NotFound extends Exception{
  public function report(){
  }

  public function render($request){
    return \CvResource::getRootInstance()->apiNotFound();
  }
}
