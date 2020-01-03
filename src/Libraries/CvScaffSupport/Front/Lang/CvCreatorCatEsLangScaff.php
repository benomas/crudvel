<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorCatEsLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'src/i18n/es/crudvuel/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/front/cv_scaff_cat_es_lang.txt';
  public function __construct(){
    parent::__construct();
  }
  //[Getters]
  //[End Getters]

  //[Setters]
  //[End Setters]

  //[Stablishers]
  public function stablishResource($resource=null){
    return $this->setResource($resource?ltrim(fixedSlug($resource),'cat-'):$resource);
  }
  //[End Stablishers]
  protected function selfRepresentation(){
    return 'cat-'.fixedSlug(Str::plural($this->getResource()));
  }
}
