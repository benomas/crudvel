<?php

namespace Crudvel\Libraries\CvScaffSupport\Front;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

abstract class CvBaseAdderScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseAdderScaff implements CvScaffInterface
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
    $crudvelFrontPath = config("packages.benomas.crudvel.crudvel.crudvel_front_path");
    return $this->setAbsolutFilePath(base_path().'/../'.$crudvelFrontPath.$this->getRelatedFilePath());
  }
//[End Stablishers]

  public function scaff() {
    return $this->processPaths()
      ->loadFile()
      ->fixFile()
      ->inyectFixedFile();
  }
}
