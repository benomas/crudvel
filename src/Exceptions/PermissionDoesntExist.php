<?php namespace Crudvel\Exceptions;

use Exception;

class PermissionDoesntExist extends \Crudvel\Exceptions\ValidationException{
  protected function getResourceLang(): string {
    return (string) 'permissions';
  }

  public function render($request){
    return \Crudvel\Controllers\ApiController::sApiFailResponse([
      "message"   => "The permission {$this->getMessage()} doest exist",
      "errorType" => $this->getErrorType(),
      "exceptionData"=>$this->getExceptionData()
    ]);
  }
}
