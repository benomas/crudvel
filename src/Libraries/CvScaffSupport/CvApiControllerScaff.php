<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvApiControllerScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/api_controller.txt");
  }

  protected function getTemplateReceptorPath(){
    $controllerName = Str::studly(Str::singular($this->getResource())).'Controller.php';
    return app_path('Http/Controllers/Api/'.$controllerName);
  }
}
