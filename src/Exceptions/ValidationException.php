<?php namespace Crudvel\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cache;

abstract class ValidationException extends Exception{
  use \Crudvel\Libraries\Helpers\CasesTrait;

  abstract protected function getResourceLang():string;

  public function report(){
  }

  public function getErrorType (){
    return $this->cvSnakeCase(class_basename(get_class($this)));
  }

  public function render($request){
    return \Crudvel\Controllers\ApiController::sApiFailResponse([
      "message"   => __("crudvel/{$this->getResourceLang()}.exceptions.{$this->getErrorType()}"),
      "errorType" => $this->getErrorType()
    ]);
  }
}
