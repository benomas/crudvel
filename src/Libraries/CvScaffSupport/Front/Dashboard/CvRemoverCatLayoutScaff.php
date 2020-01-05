<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Dashboard;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRemoverCatLayoutScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseRemoverScaff implements CvScaffInterface
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
  protected function fixFile(){
    cvConsoler(
      cvInfo('layout file needs to be fixed manually ['.
      $this->getAbsolutFilePath().
      "]\n")
    );
    return $this;
    return $this->globalFileRegexRemover(
      $this->scapedRegexMaker(
        '\'<slot>\'',
        Str::camel(Str::plural($this->getResource()))
      )
    );
  }
//[End Stablishers]
  protected function selfRepresentation(){
    return 'layout';
  }
}
