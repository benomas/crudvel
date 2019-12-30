<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\ApiController;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorApiControllerScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }
  //[Getters]
  protected function getTargetFileName(){
    return parent::getTargetFileName().'Controller.php';
  }
  //[End Getters]

  //[Setters]
  //[End Setters]

  //[Stablishers]
  //[End Stablishers]
}
