<?php

namespace Crudvel\Libraries\CvScaffSupport\Finals;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvBackCatEnLangScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/cv_back_cat_en_lang.txt");
  }

  protected function getTemplateReceptorPath(){
    $langName = fixedSlug(Str::plural($this->getResource())).'.php';
    return resource_path('lang/en/crudvel/'.$langName);
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
