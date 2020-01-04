<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorCatEnLangScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'resources/lang/en/crudvel/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_scaff_cat_en_lang.txt';
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