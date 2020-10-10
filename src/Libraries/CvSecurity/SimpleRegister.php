<?php

namespace Crudvel\Libraries\CvSecurity;

use Crudvel\Libraries\CvSecurity\RegisterInterface;

class SimpleRegister implements RegisterInterface{
// [Specific Logic]
  public function registerFlow():RegisterInterface{
    return $this;
  }

  public function response(){

  }
// [End Specific Logic]
// [Getters]
// [End Getters]
// [Setters]
// [End Setters]
}
