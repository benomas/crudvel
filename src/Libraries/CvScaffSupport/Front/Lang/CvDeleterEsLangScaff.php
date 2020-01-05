<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvDeleterEsLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseDeleterScaff implements CvScaffInterface
{
  protected $relatedFilePath   = 'src/i18n/es/crudvuel/';
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
    return fixedSlug(Str::plural($this->getResource()));
  }
}
