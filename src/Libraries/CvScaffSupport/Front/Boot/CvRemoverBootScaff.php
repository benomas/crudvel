<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Boot;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRemoverBootScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseRemoverScaff implements CvScaffInterface
{
  protected $relatedFilePath   = 'src/boot/boot.js';
  public function __construct(){
    parent::__construct();
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
  protected function fixFile(){
    return $this->fixImportSection()->fixResourcesSection();
  }
//[End Stablishers]
  protected function selfRepresentation(){
    return 'boot';
  }

  private function fixImportSection(){
    return $this->globalFileRegexRemover(
      $this->scapedRegexMaker(
        'import\s+<slot>\s+from\s+\'src\/resource-definitions\/<slot>\'',
        Str::camel(Str::plural($this->getResource())),
        Str::camel(Str::plural($this->getResource()))
      )
    );
  }

  private function fixResourcesSection(){
    return $this->globalFileRegexRemover(
      $this->scapedRegexMaker(
        '\'<slot>\'\s*:\s*<slot>\(cvGlobDep.globals\)',
        Str::camel(Str::plural($this->getResource())),
        Str::camel(Str::plural($this->getResource()))
      )
    );
  }
}
