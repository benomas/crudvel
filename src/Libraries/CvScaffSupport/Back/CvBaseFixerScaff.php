<?php

namespace Crudvel\Libraries\CvScaffSupport\Back;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

class CvBaseFixerScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseFixerScaff implements CvScaffInterface
{
  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
  }

//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
//[End Stablishers]
}
