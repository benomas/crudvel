<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvDeleterCatEnLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseDeleterScaff implements CvScaffInterface
{
  //! dont use CvScaffCatTrait in cat-lang files;
  protected $relatedFilePath = 'resources/lang/en/crudvel/';
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
    return $this->setResource($resource?ltrim(fixedSlug($resource),'cat-'):$resource);
  }
//[End Stablishers]
  protected function selfRepresentation(){
    return 'cat-'.fixedSlug(Str::plural($this->getResource()));
  }
}
