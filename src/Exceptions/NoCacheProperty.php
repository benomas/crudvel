<?php namespace Crudvel\Exceptions;

use Exception;

class NoCacheProperty extends Exception{
  public function render($request){
    return response()->json(
      ["message"=>trans("crudvel.api.no_cache_property")]
      ,422
    );
  }
}
