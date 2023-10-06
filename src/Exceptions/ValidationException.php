<?php namespace Crudvel\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cache;

abstract class ValidationException extends Exception{
  use \Crudvel\Libraries\Helpers\CasesTrait;
  protected $exceptionData = [];

  abstract protected function getResourceLang():string;

// [Specific Logic]
  public function report(){
  }

  public function getErrorType (){
    return $this->cvSnakeCase(class_basename(get_class($this)));
  }

  public function render($request){
    return \Crudvel\Controllers\ApiController::sApiFailResponse([
      "message"   => trim(__("crudvel/{$this->getResourceLang()}.exceptions.{$this->getErrorType()}").
      " {$this->getMessage()} "),
      "errorType" => $this->getErrorType(),
      "exceptionData"=>$this->getExceptionData()
    ]);
  }
// [End Specific Logic]
// [Getters]
  public function getExceptionData(){
    return $this->exceptionData??null;
  }
// [End Getters]
// [Setters]
  public function setExceptionData($exceptionData=null){
    $this->exceptionData = $exceptionData??null;

    return $this;
  }
// [End Setters]
}
