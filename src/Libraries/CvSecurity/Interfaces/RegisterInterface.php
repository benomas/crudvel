<?php

namespace Crudvel\Libraries\CvSecurity\Interfaces;

interface RegisterInterface{
  public function registerFlow():RegisterInterface;
  public function response();
}
