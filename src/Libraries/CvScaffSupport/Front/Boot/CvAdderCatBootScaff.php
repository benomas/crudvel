<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Boot;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderCatBootScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseAdderScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath   = 'src/boot/boot.js';

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
    return $this->fixImportSection()->fixResourcesSection();
  }

  protected function selfRepresentation(){
    return 'boot';
  }

  private function fixImportSection(){
    $basePatern       = 'import\s+<slot>\s+from\s+\'src\/resource-definitions\/<slot>\'';
    $camelResource    = Str::camel(Str::plural($this->getResource()));

    return $this->globalFileRegexAdder(
      $this->regexMaker($basePatern,'[^\s,]+','[^\s,]+'),
      $this->scapedRegexMaker($basePatern,$camelResource,$camelResource),
      'import '.$camelResource.' from '.'\'src/resource-definitions/'.$camelResource.'\''
    );
  }

  private function fixResourcesSection(){
    $basePatern    = '\'<slot>\'\s*:\s*<slot>\(store\)';
    $camelResource    = Str::camel(Str::plural($this->getResource()));

    return $this->globalFileRegexAdder(
      $this->regexMaker($basePatern,'[^\s,]+','[^\s,]+'),
      $this->scapedRegexMaker($basePatern,$camelResource,$camelResource),
      '\''.$camelResource.'\' : '.$camelResource.'(store)'
    );
  }
}
