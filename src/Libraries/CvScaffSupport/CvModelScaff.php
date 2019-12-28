<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvModelScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/model.txt");
  }

  protected function getTemplateReceptorPath(){
    $modelName = Str::studly(Str::singular($this->getResource())).'.php';
    return app_path('Models/'.$modelName);
  }
}
