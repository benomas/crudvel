<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRemoverCrudvuelCatPtLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseRemoverScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath   = 'src/i18n/pt/crudvuel.js';
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
    return $this->
    setLeftRegexElementRemover('(import(?>\s|\S)*?)(\s+)(,?)(\s*)')->
    setRightRegexElementRemover('(\s*)(,?)((?>\s|\S)*?)')->
    globalFileRegexRemover(
      $this->scapedRegexMaker(
        'import\s+<slot>\s+from\s+\'\.\/crudvuel\/<slot>\'',
        Str::camel(Str::plural($this->getResource())),
        cvSlugCase(Str::plural($this->getResource()))
      )
    );
  }

  private function fixCrudvuelLangsSection(){
    return $this->
    setLeftRegexElementRemover('(crudvuelLangs.resources = {(?>\s|\S)*?)(\s+)(,?)(\s*)')->
    setRightRegexElementRemover('(\s*)(,?)((?>\s|\S)*?)')->
    globalFileRegexRemover(
      $this->scapedRegexMaker(
        '\'<slot>\'\s*:\s*resourceMixer\(<slot>\)',
        cvSlugCase(Str::plural($this->getResource())),
        Str::camel(Str::plural($this->getResource()))
      )
    );
  }
}
