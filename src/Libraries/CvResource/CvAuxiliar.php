<?php

namespace Crudvel\Libraries\CvResource;

class CvAuxiliar{
  use \Crudvel\Traits\CrudTrait;
  public function __construct(){
    $this->injectCvResource();
  }
}
