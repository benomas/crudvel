<?php namespace Crudvel\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cache;

class NotFound extends Exception{
  public function report(){
  }

  public function render($request){
    return \CvResource::getRootInstance()->apiNotFound();
  }
}
