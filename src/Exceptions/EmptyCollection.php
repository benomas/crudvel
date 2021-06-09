<?php namespace Crudvel\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cache;

class EmptyCollection extends Exception{
  public function report(){
  }

  public function render($request){
    return \CvResource::getRootInstance()->apiSuccessResponse([
      "data"    => [],
      "count"   => 0,
      "message" => trans("crudvel.api.success")
    ]);
  }
}
