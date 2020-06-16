<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Router;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderCatRouterScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseAdderScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath   = 'src/router/CvPrivateInternalRouter.js';
  public function __construct(){
    parent::__construct();
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
//[End Stablishers]
  protected function fixFile(){
    $basePatern       = '\.\.\.this\.mGetStResources\(\)\.<slot>\.getRoutes\(\)';
    $camelResource    = Str::camel(Str::plural($this->getResource()));
    return $this->globalFileRegexAdder(
      $this->regexMaker($basePatern,'[^\s,]+'),
      $this->scapedRegexMaker($basePatern,$camelResource),
      '...this.mGetStResources().'.$camelResource.'.getRoutes()'
    );
  }

  protected function selfRepresentation(){
    return 'router';
  }
}
