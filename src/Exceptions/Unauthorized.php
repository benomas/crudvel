<?php namespace Crudvel\Exceptions;

use Exception;

class Unauthorized extends Exception {
  public function render($request) {
    return \CvResource::getRootInstance()->apiUnauthorized();
  }
}
