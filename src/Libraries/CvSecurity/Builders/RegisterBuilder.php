<?php

namespace Crudvel\Libraries\CvSecurity\Builders;

use Crudvel\Libraries\CvSecurity\Exceptions\{
  NoControllerException,
  NoRegisterException
};

use Crudvel\Libraries\CvSecurity\Interfaces\{
  RegisterInterface,
  RegisterBuilderInterface,
  RegisterInvokerInterface
};

class RegisterBuilder implements RegisterBuilderInterface{
  protected $userControllerInstance;
  protected $registers = [
    'simple-register'=>\Crudvel\Libraries\CvSecurity\Registers\SimpleRegister::class
  ];

  public function __construct(){
  }
// [Specific Logic]
  public function build():RegisterInterface{
    if(!$this->getUserControllerInstance())
      throw new NoControllerException();

    $registerClass = $this->selectRegister();
    return (new $registerClass)->setUserControllerInstance($this->getUserControllerInstance());
  }

  public function selectRegister(){
    $registerClass = $this->getRegisters()['simple-register']??null;

    if(!$registerClass || !class_exists($registerClass))
      throw new NoRegisterException();

    return $registerClass;
  }
// [End Specific Logic]
// [Getters]
  public function getUserControllerInstance():RegisterInvokerInterface{
    return $this->userControllerInstance??null;
  }

  public function getRegisters():Array{
    return $this->registers??null;
  }
// [End Getters]
// [Setters]
  public function setUserControllerInstance(RegisterInvokerInterface $userControllerInstance=null):RegisterBuilderInterface{
    $this->userControllerInstance = $userControllerInstance??null;

    return $this;
  }

  public function setRegisters($registers=null){
    $this->registers = $registers??null;

    return $this;
  }
// [End Setters]
}
