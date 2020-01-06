<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvDeleterCatEnLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseDeleterScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath   = 'src/i18n/en/crudvuel/';
  public function __construct(){
    parent::__construct();
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
//[Stablishers]
  public function stablishResource($resource=null){
    return $this->setResource($this->unCat($resource));
  }
//[End Stablishers]
  protected function selfRepresentation(){
    return fixedSlug(Str::plural($this->reCat($this->getResource())));
  }
}
