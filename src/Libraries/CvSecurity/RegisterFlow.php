<?php

namespace Crudvel\Libraries\CvSecurity;

use Crudvel\Controllers\ApiController;

class RegisterFlow{
  protected $userControllerInstance;
  public function __construct(){

  }
// [Specific Logic]
// [End Specific Logic]
// [Getters]
  public function getUserControllerInstance():ApiController{
    return $this->userControllerInstance??null;
  }
// [End Getters]
// [Setters]
  public function setUserControllerInstance(ApiController $userControllerInstance=null):RegisterFlow{
    $this->userControllerInstance = $userControllerInstance??null;

    return $this;
  }
// [End Setters]
}
