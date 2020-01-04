<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Request;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorRequestScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'app/Http/Requests/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_scaff_request.txt';
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
    return Str::studly(Str::singular($this->getResource())).'Request';
  }
}
