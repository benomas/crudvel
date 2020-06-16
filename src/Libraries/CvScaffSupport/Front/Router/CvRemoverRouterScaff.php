<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Router;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRemoverRouterScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseRemoverScaff implements CvScaffInterface
{
  protected $relatedFilePath   = 'src/router/Router.js';
  public function __construct(){
    parent::__construct();
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
  protected function fixFile(){
    return $this->globalFileRegexRemover(
      $this->scapedRegexMaker(
        'this\.mGetStResources\(\):plural($this->getResource()))
      )
    );
  }
//[End Stablishers]
  protected function selfRepresentation(){
    return 'router';
  }
}
