<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvDeleterCatEnLangScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseDeleterScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath = 'resources/lang/en/crudvel/';
  public function __construct(){
    parent::__construct();
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
  public function stablishResource($resource=null){
    return $this->setResource($this->unCat($resource));
  }
//[End Stablishers]
  protected function selfRepresentation(){
    return cvSlugCase(Str::plural($this->reCat($this->getResource())));
  }
}