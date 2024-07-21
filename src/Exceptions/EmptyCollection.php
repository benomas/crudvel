<?php namespace Crudvel\Exceptions;

use Exception;

class EmptyCollection extends Exception {
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
