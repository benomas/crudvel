<?php namespace Crudvel\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class NoCacheProperty extends Exception {
  public function report() {
  }

  public function render($request): JsonResponse {
    return response()->json(
      ["message"=>trans("crudvel.api.no_cache_property")]
      ,422
    );
  }
}
