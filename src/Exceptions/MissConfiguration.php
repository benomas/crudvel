<?php namespace Crudvel\Exceptions;

use Exception;

class MissConfiguration extends Exception{
  public function render($request){
    return \CvResource::getRootInstance()->apiMissConfiguration();
  }
}
