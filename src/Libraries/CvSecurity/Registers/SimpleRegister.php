<?php

namespace Crudvel\Libraries\CvSecurity\Registers;

use Crudvel\Libraries\CvSecurity\Interfaces\{
  RegisterInterface,
  RegisterInvokerInterface
};

use Crudvel\Libraries\CvSecurity\Exceptions\{
  NoControllerException
};

class SimpleRegister implements RegisterInterface{
  protected $userControllerInstance;
  protected $successRegistration;
// [Specific Logic]
  public function registerFlow():RegisterInterface{
    if(!$this->getUserControllerInstance())
      throw new NoControllerException();

    $this->setSuccessRegistration($this->getUserControllerInstance()->fixRegisterUserData()->persist(function(){
      $roleModelClass = get_class($this->getUserControllerInstance()->getModelCollectionInstance()->roles()->getRelated());
      $this->getUserControllerInstance()->getModelCollectionInstance()->roles()->attach($roleModelClass::slug('min-role')->first()->id);

      return true;
    }));

    return $this;
  }

  public function response(){
    return $this->getSuccessRegistration()?
    $this->getUserControllerInstance()->apiSuccessResponse($this->getUserControllerInstance()->getModelCollectionInstance()):
      $this->getUserControllerInstance()->apiFailResponse();
  }
// [End Specific Logic]
// [Getters]
  public function getUserControllerInstance():RegisterInvokerInterface{
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
