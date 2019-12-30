<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

class CvBaseCreatorScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  protected $mode='creator';
  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
  }
}
