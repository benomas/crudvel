<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Service;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorServiceScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'src/services/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/front/cv_scaff_service.txt';
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
