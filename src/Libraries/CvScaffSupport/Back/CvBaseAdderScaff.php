<?php

namespace Crudvel\Libraries\CvScaffSupport\Back;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

class CvBaseAdderScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseAdderScaff implements CvScaffInterface
{
  protected $type='adder';
  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
  }
}
