<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Router;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderRouterScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseAdderScaff implements CvScaffInterface
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
//[End Stablishers]
  protected function fixFile(){
    $basePatern       = 'this\.resources\.<slot>\.getRoutes\(\)';
    $camelResource    = Str::camel(Str::plural($this->getResource()));
    return $this->globalFileRegexAdder(
      $this->regexMaker($basePatern,'[^\s,]+'),
      $this->scapedRegexMaker($basePatern,$camelResource),
      'this.resources.'.$camelResource.'.getRoutes()'
    );
  }
  protected function selfRepresentation(){
    return 'router';
  }
}
