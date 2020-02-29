<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Crud;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorFillerScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseCreatorScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $fileExtension       = '.vue';
  protected $relatedTargetPath   = 'src/components/fillers/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/front/cv_scaff_filler.txt';
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
    return fixedSlug(Str::plural($this->getResource())).'/'.Str::studly(Str::singular($this->getResource()));
  }
}
