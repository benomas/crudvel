<?php

namespace Crudvel\Libraries\CvScaffSupport\Front;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

abstract class CvBaseAdderScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseAdderScaff implements CvScaffInterface
{
  protected $type='adder';
  use \Crudvel\Traits\CvScaffBaseTrait;

  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
  }
}
