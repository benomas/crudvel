<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRemoverCrudvuelEsLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseRemoverScaff implements CvScaffInterface
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
  protected function fixFile(){
    return $this->fixImportSection()->fixCrudvuelLangsSection();
  }
//[End Stablishers]
  protected function selfRepresentation(){
    return 'crudvuel';
  }

  private function fixImportSection(){
    return $this->globalFileRegexRemover(
      $this->scapedRegexMaker(
        'import\s+<slot>\s+from\s+\'\.\/crudvuel\/<slot>\'',
        Str::camel(Str::plural($this->getResource())),
        fixedSlug(Str::plural($this->getResource()))
      )
    );
  }

  private function fixCrudvuelLangsSection(){
    return $this->globalFileRegexRemover(
      $this->scapedRegexMaker(
        '\'<slot>\'\s*:\s*resourceMixer\(<slot>\)',
        fixedSlug(Str::plural($this->getResource())),
        Str::camel(Str::plural($this->getResource()))
      )
    );
  }
}
