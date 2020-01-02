<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Request;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvDeleterRequestScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseDeleterScaff implements CvScaffInterface
{
  protected $relatedFilePath   = 'app/Http/Requests/';
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
