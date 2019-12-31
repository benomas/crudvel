<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Model;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorModelScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'app/Models/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_scaff_model.txt';
  public function __construct(){
    parent::__construct();
  }
  //[Getters]
  protected function getTargetFileName(){
    return parent::getTargetFileName().'.php';
  }
  //[End Getters]

  //[Setters]
  //[End Setters]

  //[Stablishers]
  //[End Stablishers]
}
