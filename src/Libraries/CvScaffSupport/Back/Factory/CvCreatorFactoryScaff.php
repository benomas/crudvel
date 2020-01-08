<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Factory;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorFactoryScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'database/factories/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_scaff_factory.txt';
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
    return Str::studly(Str::singular($this->getResource())).'Factory';
  }
}
