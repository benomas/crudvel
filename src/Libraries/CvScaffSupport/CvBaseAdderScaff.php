<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

class CvBaseAdderScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  protected $mode='adder';
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
