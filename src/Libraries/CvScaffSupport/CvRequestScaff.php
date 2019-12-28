<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRequestScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/request.txt");
  }

  protected function getTemplateReceptorPath(){
    $requestName = Str::studly(Str::singular($this->getResource())).'Request.php';
    return app_path('Http/Requests/'.$requestName);
  }
}
