<?php namespace Crudvel\Exceptions;

use Crudvel\Controllers\ApiController;
use Crudvel\Libraries\Helpers\CasesTrait;
use Exception;
use Illuminate\Http\JsonResponse;

abstract class ValidationException extends Exception {
  use CasesTrait;
  protected mixed $exceptionData;

  abstract protected function getResourceLang():string;

  public function __construct($message = null, $code = 0, \Exception $previous = null){
    $message = $message ? " - $message":'';
    $message = "{$this->message}{$message}";
    parent::__construct($message, $code, $previous);
    $this->message = "Error found at line {$this->getLine()} in {$this->getFile()}: <b>{$this->getMessage()}</b>";
  }

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

  public function render($request): JsonResponse {
    return ApiController::sApiFailResponse([
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
