<?php namespace Crudvel\Exceptions;

use Crudvel\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class PermissionDoesntExist extends ValidationException {
  protected function getResourceLang(): string {
    return (string) 'permissions';
  }

  public function render($request): JsonResponse {
    return ApiController::sApiFailResponse([
      "message"   => "The permission {$this->getMessage()} doest exist",
      "errorType" => $this->getErrorType(),
      "exceptionData"=>$this->getExceptionData()
    ]);
  }
}
