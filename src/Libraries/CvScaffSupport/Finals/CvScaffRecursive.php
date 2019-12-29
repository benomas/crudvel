<?php

namespace Crudvel\Libraries\CvScaffSupport\Finals;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvScaffRecursive extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/cv_scaff.txt");
  }

  protected function getTemplateReceptorPath(){
    $baseName = $this->getExtraParams()['context (define as back or front)']??'';
    $baseName .= '_'.($this->getExtraParams()['prefix']??'');
    $baseName .= '_'.($this->getExtraParams()['resource']??'');
    $scaffName = 'Cv'.Str::studly(Str::singular($baseName)).'Scaff.php';
    return base_path('vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Finals/'.$scaffName);
  }
  //[LoadTemplate Modes]
  //[End LoadTemplate Modes]

  //[CalculateParams Modes]
  //[End CalculateParams Modes]

  //[FixTemplate Modes]
  //[End FixTemplate Modes]

  //[InyectFixedTemplate Modes]
  //[End InyectFixedTemplate Modes]
}
