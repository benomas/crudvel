<?php namespace Crudvel\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cache;

class Unauthorized extends Exception{
  public function render($request){
    return \CvResource::getRootInstance()->apiUnauthorized();
  }
}
