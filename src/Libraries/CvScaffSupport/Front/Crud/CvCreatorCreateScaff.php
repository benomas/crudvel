<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Crud;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorCreateScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $fileExtension       = '.vue';
  protected $relatedTargetPath   = 'src/components/resources/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/front/cv_scaff_create.txt';
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
    return cvSlugCase(Str::plural($this->getResource())).'/CvCreate';
  }
}
