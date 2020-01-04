<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Seed;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvDeleterCatSeederScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseDeleterScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath   = 'database/seeds/';
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
