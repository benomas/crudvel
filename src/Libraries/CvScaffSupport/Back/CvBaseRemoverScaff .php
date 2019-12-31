<?php

namespace Crudvel\Libraries\CvScaffSupport\Back;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

abstract class CvBaseRemoverScaff  extends \Crudvel\Libraries\CvScaffSupport\CvBaseRemoverScaff implements CvScaffInterface
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
