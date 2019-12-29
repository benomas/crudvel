<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvWevControllerScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    $fileName = ltrim('wev_controller.txt','_');
    return base_path("vendor/benomas/crudvel/src/templates/$fileName");
  }

  protected function getTemplateReceptorPath(){
    $controllerName = Str::studly(Str::singular($this->getResource())).'Controller.php';
    return app_path('Http/Controller/Wev/'.$controllerName);
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
