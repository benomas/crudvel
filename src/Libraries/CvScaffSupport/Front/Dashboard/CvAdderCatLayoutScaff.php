<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Dashboard;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderCatLayoutScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseAdderScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath             = 'src/layouts/private-navigation.vue';
  protected $leftRegexGlobalRequiriment  = 'catalogResources';
  protected $rightRegexGlobalRequiriment = ']';
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
    cvConsoler(
      cvInfo('layout file needs to be fixed manually ['.
      $this->getAbsolutFilePath().
      "]\n")
    );
    return $this;
    $basePatern       = '\'<slot>\'';
    $camelResource    = Str::camel(Str::plural($this->getResource()));
    return $this->globalFileRegexAdder(
      $this->regexMaker($basePatern,'[^\s,]+'),
      $this->scapedRegexMaker($basePatern,$camelResource),
      '\'.$camelResource.\''
    );
  }
  protected function selfRepresentation(){
    return 'layout';
  }
}
