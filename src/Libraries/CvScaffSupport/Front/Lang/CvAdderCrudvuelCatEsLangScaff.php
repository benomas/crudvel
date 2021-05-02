<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderCrudvuelCatEsLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseAdderScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
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
    $basePatern       = 'import\s+<slot>\s+from\s+\'\.\/crudvuel\/<slot>\'';
    $slugResource     = cvSlugCase(Str::plural($this->getResource()));
    $camelResource    = Str::camel(Str::plural($this->getResource()));

    return $this->
      setLeftRegexElementAdder('(import(?>\s|\S)*?)(\s+)(,?)(\s*)')->
      setRightRegexElementAdder('(\s*)(,?)((?>\s|\S)*?)')->
      globalFileRegexAdder(
        $this->regexMaker($basePatern,'[^\s,]+','[^\s,]+'),
        $this->scapedRegexMaker($basePatern,$camelResource,$slugResource),
        'import '.$camelResource.' from '.'\'./crudvuel/'.$slugResource.'\''
      );
  }

  private function fixCrudvuelLangsSection(){
    $basePatern    = '\'<slot>\'\s*:\s*resourceMixer\(<slot>\)';
    $slugResource     = cvSlugCase(Str::plural($this->getResource()));
    $camelResource    = Str::camel(Str::plural($this->getResource()));

    return $this->
      setLeftRegexElementAdder('(crudvuelLangs.resources = {(?>\s|\S)*?)(\s+)(,?)(\s*)')->
      setRightRegexElementAdder('(\s*)(,?)((?>\s|\S)*?)')->
      globalFileRegexAdder(
        $this->regexMaker($basePatern,'[^\s,]+','[^\s,]+'),
        $this->scapedRegexMaker($basePatern,$slugResource,$camelResource),
        '\''.$slugResource.'\' : resourceMixer('.$camelResource.')'
      );
  }
}
