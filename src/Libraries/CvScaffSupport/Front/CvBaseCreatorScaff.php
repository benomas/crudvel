<?php

namespace Crudvel\Libraries\CvScaffSupport\Front;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

abstract class CvBaseCreatorScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseCreatorScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffBaseTrait;
  protected $fileExtension='.js';
  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
  }

//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
  protected function stablishAbsolutTargetPath(){
    $crudvelFrontPath = config("packages.benomas.crudvel.crudvel.crudvel_front_path");
    return $this->setAbsolutTargetPath(base_path().'/../'.$crudvelFrontPath.$this->getRelatedTargetPath());
  }
//[End Stablishers]
}
