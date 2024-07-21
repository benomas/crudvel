<?php namespace Crudvel\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cache;

abstract class ValidationException extends Exception {
  use \Crudvel\Libraries\Helpers\CasesTrait;
  protected mixed $exceptionData;

  abstract protected function getResourceLang():string;

// [Specific Logic]
  public function report(){
  }

  public function getErrorType (): string {
    return $this->cvSnakeCase(class_basename(get_class($this)));
  }

  public function getLangMessage ():string {
    return trim(__("crudvel/{$this->getResourceLang()}.exceptions.{$this->getErrorType()}").
      " {$this->getMessage()} ");
  }

  public function render($request): \Illuminate\Http\JsonResponse {
    return \Crudvel\Controllers\ApiController::sApiFailResponse([
      "message"       => $this->getLangMessage(),
      "errorType"     => $this->getErrorType(),
      "exceptionData" => $this->getExceptionData()
    ]);
  }
// [End Specific Logic]
// [Getters]
  public function getExceptionData():mixed {
    return $this->exceptionData??null;
  }
// [End Getters]
// [Setters]
  public function setExceptionData(mixed $exceptionData=null): static {
    $this->exceptionData = $exceptionData??null;

    return $this;
  }
// [End Setters]
}
