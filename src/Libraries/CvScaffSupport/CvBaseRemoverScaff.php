<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

abstract class CvBaseRemoverScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseAdderScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffBaseTrait;
  use \Crudvel\Traits\CvScaffRegexTrait;
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