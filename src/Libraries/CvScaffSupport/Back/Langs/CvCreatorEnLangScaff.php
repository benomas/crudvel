<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Langs;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorEnLangScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'app/resources/lang/en/crudvel/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_en_lang.txt';
  public function __construct(){
    parent::__construct();
  }
  //[Getters]
  protected function getTargetFileName(){
    return $this->getAbsolutTargetPath().fixedSlug(Str::plural($this->getResource())).'.php';
  }
  //[End Getters]

  //[Setters]
  //[End Setters]

  //[Stablishers]
  //[End Stablishers]
}
