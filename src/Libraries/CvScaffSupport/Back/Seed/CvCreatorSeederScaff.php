<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Seed;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorSeederScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'database/seeds/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_scaff_seeder.txt';
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
    return Str::studly(Str::singular($this->getResource())).'TableSeeder';
  }
}