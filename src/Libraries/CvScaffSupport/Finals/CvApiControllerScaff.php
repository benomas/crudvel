<?php

namespace Crudvel\Libraries\CvScaffSupport\Finals;

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
  //[LoadTemplate Modes]
  //[End LoadTemplate Modes]

  //[CalculateParams Modes]
  //[End CalculateParams Modes]

  //[FixTemplate Modes]
  //[End FixTemplate Modes]

  //[InyectFixedTemplate Modes]
  //[End InyectFixedTemplate Modes]
}
