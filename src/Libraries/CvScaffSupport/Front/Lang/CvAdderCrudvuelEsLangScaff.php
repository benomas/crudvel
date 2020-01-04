<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderCrudvuelEsLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseAdderScaff implements CvScaffInterface
{
  protected $relatedFilePath   = 'src/i18n/es/crudvuel.js';
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
    return $this->fixImportSection()->fixCrudvuelLangsSection();
  }
  protected function selfRepresentation(){
    return 'crudvuel';
  }
  private function fixImportSection(){
    $basePatern       = '/import\s+<slot>\s+from\s+\'\.\/crudvuel\/<slot>\'/';
    $slugResource     = fixedSlug(Str::plural($this->getResource()));
    $camelResource    = Str::camel(Str::plural($this->getResource()));
    return $this->globalFileRegexAdder(
      $this->regexMaker($basePatern,'[^\s,]+','[^\s,]+'),
      $this->scapedRegexMaker($basePatern,$camelResource,$slugResource),
      'import '.$camelResource.' from '.'\'./crudvuel/'.$slugResource.'\''
    );
  }
  private function fixCrudvuelLangsSection(){
    $basePatern    = '/\'<slot>\'\s*:\s*resourceMixer\(<slot>\)/';
    $slugResource     = fixedSlug(Str::plural($this->getResource()));
    $camelResource    = Str::camel(Str::plural($this->getResource()));
    return $this->globalFileRegexAdder(
      $this->regexMaker($basePatern,'[^\s,]+','[^\s,]+'),
      $this->scapedRegexMaker($basePatern,$camelResource,$slugResource),
      '\''.$slugResource.'\' : resourceMixer('.$camelResource.')'
    );
  }
}