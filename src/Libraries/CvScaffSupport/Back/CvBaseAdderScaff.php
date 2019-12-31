<?php

namespace Crudvel\Libraries\CvScaffSupport\Back;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

abstract class CvBaseAdderScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseAdderScaff implements CvScaffInterface
{
  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
  }

  abstract protected function fixFile();

//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
//[End Stablishers]
}
