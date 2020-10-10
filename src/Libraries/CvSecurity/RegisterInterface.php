<?php

namespace Crudvel\Libraries\CvSecurity;

interface RegisterInterface{
  public function registerFlow():RegisterInterface;
  public function response();
}
