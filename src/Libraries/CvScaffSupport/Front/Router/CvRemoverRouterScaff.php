<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Router;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRemoverRouterScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseRemoverScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }
  //[Getters]
  //[End Getters]

  //[Setters]
  //[End Setters]

  //[Stablishers]
  //[End Stablishers]
  protected function selfRepresentation(){
    return Str::studly(Str::singular($this->getResource()));
  }
}
