<?php

namespace Crudvel\Libraries\CvSecurity\Interfaces;

use Crudvel\Libraries\CvSecurity\Interfaces\RegisterInterface;

interface RegisterBuilderInterface{
  public function build():RegisterInterface;
  public function setUserControllerInstance():RegisterBuilderInterface;
}
