<?php

namespace Crudvel\Libraries\CvScaffSupport\Front;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

abstract class CvBaseDeleterScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseDeleterScaff implements CvScaffInterface
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
  protected function stablishAbsolutFilePath(){
    return $this->setAbsolutFilePath(
      base_path().'/../'.config("packages.benomas.crudvel.crudvel.crudvel_front_path").
      $this->getRelatedFilePath().
      $this->selfRepresentation().
      $this->getFileExtension()
    );
  }
//[End Stablishers]
}
