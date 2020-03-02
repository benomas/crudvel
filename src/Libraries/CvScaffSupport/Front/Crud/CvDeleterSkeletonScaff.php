<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Crud;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvDeleterSkeletonScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseDeleterScaff implements CvScaffInterface
{
  protected $fileExtension       = '.vue';
  protected $relatedFilePath     = 'src/components/resources/';
  public function __construct(){
    parent::__construct();
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
//[End Stablishers]
  protected function selfRepresentation(){
    return fixedSlug(Str::plural($this->getResource())).'/Cv'.Str::studly(Str::plural($this->getResource())).'Skeleton';
  }
}
