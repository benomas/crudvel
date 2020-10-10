<?php

namespace Crudvel\Libraries\CvSecurity;

use Crudvel\Controllers\ApiController;
use Crudvel\Libraries\CvSecurity\{NoControllerException,RegisterInterface};

class SimpleRegister implements RegisterInterface{
  protected $userControllerInstance;
  protected $successRegistration;
// [Specific Logic]
  public function registerFlow():RegisterInterface{
    if(!$this->getUserControllerInstance())
      throw new NoControllerException();

    $this->setSuccessRegistration($this->getUserControllerInstance()->fixUserData()->persist(function(){
      return true;
    }));

    return $this;
  }

  public function response(){
    return $this->getSuccessRegistration()?
      $this->getUserControllerInstance()->apiFailResponse():
      $this->getUserControllerInstance()->apiSuccessResponse($this->getUserControllerInstance()->getModelCollectionInstance());
  }
// [End Specific Logic]
// [Getters]
  public function getUserControllerInstance():ApiController{
    return $this->userControllerInstance;
  }

  public function getSuccessRegistration(){
    return $this->successRegistration??null;
  }
// [End Getters]
// [Setters]
  public function setUserControllerInstance($userControllerInstance=null):RegisterInterface{
    $this->userControllerInstance = $userControllerInstance??null;

    return $this;
  }

  public function setSuccessRegistration($successRegistration=null):RegisterInterface{
    $this->successRegistration = $successRegistration??null;

    return $this;
  }
// [End Setters]
}
