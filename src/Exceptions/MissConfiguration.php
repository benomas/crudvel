<?php namespace Crudvel\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cache;

class MissConfiguration extends Exception{
  public function render($request){
    return \CvResource::getRootInstance()->apiMissConfiguration();
  }
}
