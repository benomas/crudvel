<?php

namespace Crudvel\Libraries\CvScaffSupport\Back;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

class CvBaseCreatorScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $context='back';
  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
  }
}
