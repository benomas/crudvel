<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvDeleterEnLangScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseDeleterScaff implements CvScaffInterface
{
  protected $relatedFilePath = 'resources/lang/en/crudvel/';
  public function __construct(){
    parent::__construct();
  }
  //[Getters]
  //[End Getters]

  //[Setters]
  //[End Setters]

  //[Stablishers]
  protected function stablishAbsolutFilePath(){
    return $this->setAbsolutFilePath(base_path(
        $this->getRelatedFilePath()).
        fixedSlug(Str::plural($this->getResource()).
        '.php'
      )
    );
  }
//[End Stablis
  //[End Stablishers]
}
