<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvEsLangScaff  extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/es_lang.txt");
  }

  protected function getTemplateReceptorPath(){
    $langName = fixedSlug(Str::plural($this->getResource())).'.php';
    return resource_path('lang/es/crudvel/'.$langName);
  }

}
