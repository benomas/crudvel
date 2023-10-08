<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorEnLangScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'resources/lang/en/crudvel/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_scaff_en_lang.txt';
  public function __construct()
  {
    parent::__construct();
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
//[End Stablishers]

  protected function selfRepresentation()
  {
    return cvSlugCase(Str::plural($this->getResource()));
  }
}
