<?php

namespace Crudvel\Libraries\CvSecurity;
use Crudvel\Libraries\CvSecurity\RegisterInterface;

interface RegisterBuilderInterface{
  public function build():RegisterInterface;
  public function setUserControllerInstance():RegisterBuilderInterface;
}
